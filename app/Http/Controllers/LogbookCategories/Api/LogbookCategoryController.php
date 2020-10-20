<?php

namespace App\Http\Controllers\LogbookCategories\Api;

use App\LogbookCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\LogbookCategories\LogbookCategoryResource;

class LogbookCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator', 'auth']);
        
        $this->middleware(['role:administrator'])->except(['index', 'all']);
    }

    public function index()
    {
        return LogbookCategoryResource::collection(
            LogbookCategory::all()->filter(function ($category) {
                if ($category->supervisor_only && !auth()->user()->hasRole(['administrator', 'supervisor'])) {
                    return false;
                }

                return true;
            })
        );
    }

    public function store()
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'supervisor_only' => 'required|boolean'
        ]);

        LogbookCategory::create([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'supervisor_only' => request('supervisor_only') ? 1 : 0
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Logbook category successfully created'
            ]
        ], 200);
    }

    public function update(LogbookCategory $logbookCategory)
    {
        request()->validate([
            'supervisor_only' => 'required|boolean',
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3'
        ]);

        $logbookCategory->update([
            'supervisor_only' => request('supervisor_only') ? 1 : 0,
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ]
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Logbook category successfully updated'
            ]
        ], 200);
    }
}
