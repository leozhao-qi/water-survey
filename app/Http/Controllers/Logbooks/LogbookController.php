<?php

namespace App\Http\Controllers\Logbooks;

use App\Http\Controllers\Controller;

class LogbookController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        return view('logbooks.index');
    }
}
