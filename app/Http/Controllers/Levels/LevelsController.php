<?php

namespace App\Http\Controllers\Levels;

use App\Http\Controllers\Controller;

class LevelsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator', 'auth']);
    }

    public function index()
    {
        return view('levels.index');
    }
}
