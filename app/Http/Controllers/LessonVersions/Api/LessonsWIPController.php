<?php

namespace App\Http\Controllers\LessonVersions\Api;

use App\LessonWIP;
use App\Classes\PackageVersion;
use App\Http\Controllers\Controller;
use App\Http\Resources\LessonWIP\LessonWIPResource;

class LessonsWIPController extends Controller
{
    public function index()
    {
        (new PackageVersion())->populateWIP();
       
        return LessonWIPResource::collection(
            LessonWIP::all()
        );
    }
}
