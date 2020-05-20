<?php

namespace App\Http\Controllers\Objectives\Api;

use App\Objective;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Rules\UniqueObjectiveNumberForLesson;
use App\Http\Resources\Objectives\ObjectiveResource;

class ObjectivesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return ObjectiveResource::collection(
            Objective::all()
        );
    }

    public function store()
    {
        request()->validate([
            'lesson_id' => 'required|integer|min:1|exists:lessons,id',
            'number' => [
                'required',
                'min:1',
                Rule::unique('objectives')->where(function ($query) {
                    $query->where([
                        ['lesson_id', request('lesson_id')]
                    ]);
                })
            ],
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'type' => [
                'required',
                Rule::in(['theory', 'practical_application']),
            ]
        ]);

        Objective::create([
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
                'message' => 'Objective successfully created'
            ]
        ], 200);
    }

    public function update(Objective $objective)
    {
        request()->validate([
            'lesson_id' => 'required|integer|min:1|exists:lessons,id',
            'number' => [
                'required',
                'min:1',
                new UniqueObjectiveNumberForLesson($objective)
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
                'message' => 'Objective successfully updated'
            ]
        ], 200);
    }

    public function destroy(Objective $objective)
    {
        $objective->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Objective successfully deleted'
            ]
        ], 200);
    }
}
