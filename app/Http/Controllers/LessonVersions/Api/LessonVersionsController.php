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
}
