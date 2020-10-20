<?php

namespace App\Http\Controllers\Topics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator', 'auth']);
    }
    
    public function index()
    {
        return view('topics.index');
    }
}
