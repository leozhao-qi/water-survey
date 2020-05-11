<?php

namespace App\Http\Controllers\Levels\Api;

use App\Level;
use App\Http\Controllers\Controller;

class LevelsController extends Controller
{
    public function index()
    {
        return Level::all();
    }
}
