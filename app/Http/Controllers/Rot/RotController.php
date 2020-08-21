<?php

namespace App\Http\Controllers\Rot;

use App\Http\Controllers\Controller;

class RotController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return view('reports.rot.index');
    }
}
