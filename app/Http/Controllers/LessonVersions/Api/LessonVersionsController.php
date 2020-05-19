<?php

namespace App\Http\Controllers\LessonVersions\Api;

use App\LessonVersion;
use App\Http\Controllers\Controller;
use App\Http\Resources\LessonVersions\LessonVersionResource;

class LessonVersionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return LessonVersionResource::collection(
            LessonVersion::all()
        );
    }

    public function update(LessonVersion $lessonVersion)
    {
        request()->validate([
            'version' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($lessonVersion) {
                    if (($value !== $lessonVersion->version) && (LessonVersion::whereVersion($value)->get()->count())) {
                        $fail(ucfirst($attribute) . ' already exists.');
                    }
                }
            ],
            'valid_on' => 'required|date'
        ]);

        $lessonVersion->update([
            'version' => request('version'),
            'valid_on' => request('valid_on')
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Lesson version successfully updated'
            ]
        ], 200);
    }
}
