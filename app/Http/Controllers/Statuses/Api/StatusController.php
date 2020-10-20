<?php

namespace App\Http\Controllers\Statuses\Api;

use App\Status;
use App\Rules\UniqueCodeForStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Statuses\StatusResource;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

        $this->middleware(['role:administrator'])->except(['index']);
    }

    public function index()
    {
        return StatusResource::collection(
            Status::all()
        );
    }

    public function store()
    {
        request()->validate([
            'code' => 'required|unique:statuses,code',
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'completion' => 'required|boolean'
        ]);

        Status::create([
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
                'message' => 'Status successfully created'
            ]
        ], 200);
    }

    public function update(Status $status)
    {
        request()->validate([
            'code' => [
                'required',
                new UniqueCodeForStatus($status)
            ],
            'completion' => 'required|boolean',
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3'
        ]);

        $status->update([
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
                'message' => 'Status successfully updated'
            ]
        ], 200);
    }
}
