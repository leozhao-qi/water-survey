<?php

namespace App\Http\Controllers\Recommendations;

use App\Http\Controllers\Controller;

class RecommendationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }
    
    public function index()
    {
        return view('recommendations.index');
    }
}
