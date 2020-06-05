<?php

namespace App\Observers;

use App\Package;

class PackageObserver
{
    /**
     * Handle the package "deleted" event.
     *
     * @param  \App\Package  $package
     * @return void
     */
    public function deleted(Package $package)
    {
        // Delete all logbooks
        $package->logbooks->each->delete();
    }
}
