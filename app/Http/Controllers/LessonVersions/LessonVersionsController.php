<?php

namespace App\Http\Controllers\LessonVersions;

use App\Http\Controllers\Controller;

class LessonVersionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }
    
    public function index()
    {
        return view('lesson_versions.index');
    }

    public function create()
    {
        return view('lesson_versions.create');
    }
}
