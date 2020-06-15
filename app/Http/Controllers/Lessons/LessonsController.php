<?php

namespace App\Http\Controllers\Lessons;

use App\Http\Controllers\Controller;

class LessonsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return view('lessons.index');
    }
}
