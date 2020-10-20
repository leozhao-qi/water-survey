<?php

namespace App\Http\Controllers\Statuses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator', 'auth']);
    }

    public function index()
    {
        return view('statuses.index');
    }
}
