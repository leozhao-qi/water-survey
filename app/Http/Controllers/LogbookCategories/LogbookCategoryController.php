<?php

namespace App\Http\Controllers\LogbookCategories;

use App\Http\Controllers\Controller;

class LogbookCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator']);
    }

    public function index()
    {
        return view('logbook_entries.index');
    }
}
