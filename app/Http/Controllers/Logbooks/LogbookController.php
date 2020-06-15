<?php

namespace App\Http\Controllers\Logbooks;

use App\Http\Controllers\Controller;

class LogbookController extends Controller
{
    public function index()
    {
        return view('logbooks.index');
    }
}
