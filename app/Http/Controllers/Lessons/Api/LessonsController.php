<?php

namespace App\Http\Controllers\Lessons\Api;

use App\Lesson;
use App\Http\Controllers\Controller;
use App\Http\Resources\Lessons\LessonResource;
use App\Rules\NonDepricatedLessonAlreadyPresent;
use App\Rules\UniqueIfNotReplacingDepricatedLesson;

class LessonsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return LessonResource::collection(
            Lesson::all()
        );
    }

    public function store()
    {
        request()->validate([
            'level_id' => 'required|integer|min:1|exists:levels,id',
            'number' => [
                'required',
                new UniqueIfNotReplacingDepricatedLesson()
            ],
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
        ]);

        Lesson::create([
            'level_id' => request('level_id'),
            'number' => request('number'),
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ]
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Lesson successfully created'
            ]
        ], 200);
    }

    public function update(Lesson $lesson)
    {
        request()->validate([
            'level_id' => 'required|integer|min:1|exists:levels,id',
            'number' => 'required',
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'depricated' => [
                'required',
                'integer',
                'min:0',
                'max:1',
                new NonDepricatedLessonAlreadyPresent($lesson)
            ],
            'depricated_on' => 'required_if:depricated,1|date'
        ]);

        $lesson->update([
            'level_id' => request('level_id'),
            'number' => request('number'),
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'depricated' => request('depricated'),
            'depricated_on' => request('depricated_on')
        ]);

        // $lessons = Lesson::whereNumber($lesson->number)->get();

        // if ($lessons->count() === 2) {
            // get all apprentices

            // loop through all apprentices
                // if appointment date is null, continue
                // if deprication date on or before appointment date, continue
                    // if there are more than two depricated packages, choose the one that is closest to before the appointment date
                // end if
                // else, delete current package and replace with this one
            // end loop
        // }

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Lesson successfully updated'
            ]
        ], 200);
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Lesson successfully deleted'
            ]
        ], 200);
    }
}
