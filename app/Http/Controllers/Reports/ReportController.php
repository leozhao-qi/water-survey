<?php

namespace App\Http\Controllers\Reports;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        return view('reports.index');
    }
}
