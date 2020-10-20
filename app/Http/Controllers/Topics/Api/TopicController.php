<?php

namespace App\Http\Controllers\Topics\Api;

use App\Topic;
use App\Rules\UniqueNumberForTopic;
use App\Http\Controllers\Controller;
use App\Http\Resources\Topics\TopicResource;

class TopicController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator', 'auth']);
    }

    public function index()
    {
        return TopicResource::collection(
            Topic::all()
        );
    }

    public function store()
    {
        request()->validate([
            'number' => 'required|integer|unique:topics,number',
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3'
        ]);

        Topic::create([
            'number' => request('number'),
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ]
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Topic successfully created'
            ]
        ], 200);
    }

    public function update(Topic $topic)
    {
        request()->validate([
            'number' => [
                'required',
                'integer',
                new UniqueNumberForTopic($topic)
            ],
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3'
        ]);

        $topic->update([
            'number' => request('number'),
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ]
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Topic successfully updated'
            ]
        ], 200);
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Topic successfully deleted'
            ]
        ], 200);
    }
}
