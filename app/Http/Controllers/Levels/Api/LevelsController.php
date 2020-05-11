<?php

namespace App\Http\Controllers\Levels\Api;

use App\Level;
use App\Http\Controllers\Controller;

class LevelsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return Level::all();
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|min:3'
        ]);

        Level::create(request()->all());

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Level successfully created'
            ]
        ], 200);
    }

    public function update(Level $level)
    {
        request()->validate([
            'name' => 'required|min:3'
        ]);

        $level->update(request()->all());

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Level successfully updated'
            ]
        ], 200);
    }

    public function destroy(Level $level)
    {
        $level->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Level successfully deleted'
            ]
        ], 200);
    }
}
