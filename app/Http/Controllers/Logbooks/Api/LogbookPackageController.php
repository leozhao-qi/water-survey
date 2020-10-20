<?php

namespace App\Http\Controllers\Logbooks\Api;

use App\User;
use App\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Objectives\ObjectiveResource;
use App\Http\Resources\Logbooks\LogbookPackageResource;

class LogbookPackageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    public function index(User $user)
    {
        return LogbookPackageResource::collection(
            $user->packages
        );
    }

    public function objectives(Package $package)
    {
        return $package->lesson->objectives->map(function ($objective) {
            if ($objective['type'] === null) {
                $objective['type'] = 'not_defined';
            }

            return $objective;
        })->groupBy('type')->toArray();
    }
}
