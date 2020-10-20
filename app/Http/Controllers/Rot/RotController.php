<?php

namespace App\Http\Controllers\Rot;

use App\Http\Controllers\Controller;

class RotController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator|supervisor|head_of_operations', 'auth']);
    }

    public function index()
    {
        return view('reports.rot.index');
    }
}
