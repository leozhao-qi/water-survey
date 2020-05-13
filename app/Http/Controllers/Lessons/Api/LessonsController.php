<?php

namespace App\Http\Controllers\Lessons\Api;

use App\Lesson;
use App\Http\Controllers\Controller;
use App\Http\Resources\Lessons\LessonResource;

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
            'number' => 'required|unique:lessons,number',
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3'
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
            'depricated' => 'required|integer|min:0|max:1'
        ]);

        $lesson->update([
            'level_id' => request('level_id'),
            'number' => request('number'),
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'depricated' => request('depricated')
        ]);

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
