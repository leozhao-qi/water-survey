<?php

namespace App\Http\Controllers\Packages\Api;

use App\User;
use App\Package;
use App\Http\Controllers\Controller;
use App\Http\Resources\Packages\PackageResource;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['profile']);
    }

    public function show(User $user, Package $package)
    {
        return new PackageResource($package);
    }
}
