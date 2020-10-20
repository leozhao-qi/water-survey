<?php

namespace App\Http\Controllers\Recommendations\Api;

use App\Recommendation;
use App\Http\Controllers\Controller;
use App\Rules\UniqueCodeForRecommendation;
use App\Http\Resources\Recommendations\RecommendationResource;

class RecommendationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

        $this->middleware(['role:administrator'])->except(['index']);
    }

    public function index()
    {
        return RecommendationResource::collection(
            Recommendation::all()
        );
    }

    public function store()
    {
        request()->validate([
            'code' => 'required|unique:recommendations,code',
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'completion' => 'required|boolean'
        ]);

        Recommendation::create([
            'code' => request('code'),
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'completion' => request('completion') ? 1 : 0
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Recommendation successfully created'
            ]
        ], 200);
    }

    public function update(Recommendation $recommendation)
    {
        request()->validate([
            'code' => [
                'required',
                new UniqueCodeForRecommendation($recommendation)
            ],
            'completion' => 'required|boolean',
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3'
        ]);

        $recommendation->update([
            'code' => request('code'),
            'completion' => request('completion') ? 1 : 0,
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ]
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Recommendation successfully updated'
            ]
        ], 200);
    }
}
