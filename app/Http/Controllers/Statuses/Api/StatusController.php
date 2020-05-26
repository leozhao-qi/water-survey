<?php

namespace App\Http\Controllers\Statuses\Api;

use App\Status;
use App\Http\Controllers\Controller;

class StatusController extends Controller
{
    public function index()
    {
        return Status::select(['code', 'id', 'name'])->get();
    }
}
