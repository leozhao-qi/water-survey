<?php

namespace App\Http\Controllers\Issues\Api;

use App\User;
use App\Issue;
use Carbon\Carbon;
use App\Mail\IssueSubmitted;
use App\Mail\AdminIssueSubmitted;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
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
    
    public function store()
    {
        request()->validate([
            'title' => 'required|min:3',
            'body' => 'required|min:3'
        ]);

        $issue = Issue::create([
            'issuer_id' => auth()->id(),
            'code' => Carbon::now()->format('ymd') . uniqid(),
            'title' => request('title'),
            'body' => request('body'),
        ]);

        Mail::to(auth()->user())->send(new IssueSubmitted($issue));

        Mail::to(User::whereEmail('paul.bovis@canada.ca')->first())->send(new AdminIssueSubmitted($issue));

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Issue successfully created'
            ]
        ], 200);
    }
}
