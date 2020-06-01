<?php

namespace App\Http\Controllers\LogbookCategories\Api;

use App\LogbookCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\LogbookCategories\LogbookCategoryResource;

class LogbookCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator'])->except(['index']);
    }

    public function index()
    {
        return LogbookCategoryResource::collection(
            LogbookCategory::all()
        );
    }
}
