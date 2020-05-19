<?php

namespace App\Http\Controllers\LessonVersions\Api;

use App\LessonWIP;
use App\Classes\PackageVersion;
use App\Http\Controllers\Controller;

class LessonsWIPController extends Controller
{
    public function index()
    {
        (new PackageVersion())->populateWIP();
        
        return LessonWIP::all();
    }
}
