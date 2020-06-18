<?php

namespace App\Http\Controllers\Reports;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        // $user = User::find(93);

        // $userIds = $user->reportingStructure()['apprentice']->pluck(['id']);

        // return $user->reportingStructure()['apprentice']->pluck(['id']);
        return view('reports.index');
    }
}
