<?php

namespace App\Http\Controllers\Reports;

use App\User;
use App\Exports\UserReportExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class UsersReportController extends Controller
{
    public function download()
    {
        return Excel::download(new UserReportExport, 'users.xlsx');
    }
}
