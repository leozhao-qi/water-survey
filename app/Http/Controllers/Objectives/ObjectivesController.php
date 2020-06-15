<?php

namespace App\Http\Controllers\Objectives;

use App\Http\Controllers\Controller;

class ObjectivesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return view('objectives.index');
    }
}
