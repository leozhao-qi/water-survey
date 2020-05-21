<?php

namespace App\Http\Controllers\LessonVersions\Api;

use App\LessonWIP;
use App\ObjectiveWIP;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Rules\UniqueObjectiveNumberForLessonWIP;
use App\Http\Resources\LessonWIP\LessonWIPResource;

class ObjectivesWIPController extends Controller
{
    public function store()
    {
        request()->validate([
            'lesson_id' => 'required|integer|min:1|exists:lessons_wip,id',
            'number' => [
                'required',
                'min:1',
                Rule::unique('objectives_wip')->where(function ($query) {
                    $query->where([
                        ['lesson_id', request('lesson_id')]
                    ]);
                })
            ],
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'type' => [
                'sometimes',
                Rule::in(['', 'theory', 'practical_application']),
            ]
        ]);

        $objective = ObjectiveWIP::create([
            'lesson_id' => request('lesson_id'),
            'number' => request('number'),
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'type' => request('type')
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Objective successfully created',
                'lesson' => new LessonWIPResource(
                    LessonWIP::find($objective->lesson_id)
                )
            ]
        ], 200);
    }

    public function update(ObjectiveWIP $objective)
    {
        request()->validate([
            'lesson_id' => 'required|integer|min:1|exists:lessons_wip,id',
            'number' => [
                'required',
                'min:1',
                new UniqueObjectiveNumberForLessonWIP($objective)
            ],
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'type' => [
                'sometimes',
                Rule::in(['', 'theory', 'practical_application']),
            ]
        ]);

        $objective->update([
            'lesson_id' => request('lesson_id'),
            'number' => request('number'),
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'type' => request('type')
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Objective successfully updated',
                'lesson' => new LessonWIPResource(
                    LessonWIP::find($objective->lesson_id)
                )
            ]
        ], 200);
    }

    public function destroy(ObjectiveWIP $objective)
    {
        $objective->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Objective successfully deleted',
                'lesson' => new LessonWIPResource(
                    LessonWIP::find($objective->lesson_id)
                )
            ]
        ], 200);
    }
}
