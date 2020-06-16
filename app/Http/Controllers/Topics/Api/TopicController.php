<?php

namespace App\Http\Controllers\Topics\Api;

use App\Topic;
use App\Http\Controllers\Controller;
use App\Http\Resources\Topics\TopicResource;

class TopicController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return TopicResource::collection(
            Topic::all()
        );
    }
}
