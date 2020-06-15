<?php

namespace App\Http\Controllers\Packages;

use App\User;
use App\Package;
use App\Http\Controllers\Controller;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['profile']);
    }

    public function show(User $user, Package $package)
    {
        return view('packages.show', compact('user', 'package'));
    }
}
