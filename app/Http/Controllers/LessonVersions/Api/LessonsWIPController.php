<?php

namespace App\Http\Controllers\LessonVersions\Api;

use App\LessonWIP;
use App\Rules\UniqueLessonWIP;
use App\Classes\PackageVersion;
use App\Http\Controllers\Controller;
use App\Http\Resources\LessonWIP\LessonWIPResource;

class LessonsWIPController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }
    
    public function index()
    {
        (new PackageVersion())->populateWIP();
       
        return LessonWIPResource::collection(
            LessonWIP::all()
        );
    }

    public function store()
    {
        request()->validate([
            'level_id' => 'required|integer|min:1|exists:levels,id',
            'number' => 'required|min:1|unique:lessons_wip,number',
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'completed_in_both' => 'sometimes|boolean',
            'topic_id' => 'required|integer|exists:topics,id'
        ]);

        LessonWIP::create([
            'level_id' => request('level_id'),
            'number' => request('number'),
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'completed_in_both' => request('completed_in_both'),
            'topic_id' => request('topic_id')
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Lesson successfully created'
            ]
        ], 200);
    }

    public function update(LessonWIP $lesson)
    {
        request()->validate([
            'level_id' => 'required|integer|min:1|exists:levels,id',
            'number' => [
                'required',
                new UniqueLessonWIP($lesson)
            ],
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'completed_in_both' => 'required|boolean',
            'topic_id' => 'required|integer|exists:topics,id'
        ]);

        $lesson->update([
            'level_id' => request('level_id'),
            'number' => request('number'),
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'completed_in_both' => request('completed_in_both'),
            'topic_id' => request('topic_id')
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Lesson successfully updated',
                'lesson' => new LessonWIPResource($lesson)
            ]
        ], 200);
    }

    public function destroy(LessonWIP $lesson)
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
