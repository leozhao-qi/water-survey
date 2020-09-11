<?php

namespace App\Http\Controllers\Issues\Api;

use App\Issue;
use App\Http\Controllers\Controller;
use App\Http\Resources\Issues\IssueResource;

class IssuesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return IssueResource::collection(
            Issue::orderBy('code')->get()
        );
    }    
}
