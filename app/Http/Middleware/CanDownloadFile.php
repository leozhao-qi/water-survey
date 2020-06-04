<?php

namespace App\Http\Middleware;

use Closure;

class CanDownloadFile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            abort(404);
        }

        if (auth()->user()->hasRole('administrator')) {
            return $next($request);
        }

        if ($this->authenticatedUsersProfile()) {
            return $next($request);
        }

        foreach(auth()->user()->reportingStructure()->flatten(1) as $employee) {
            if (
                $employee['id'] === (int) request()->query('user') && 
                $employee['rank'] > auth()->user()->roles->first()->rank && 
                auth()->user()->active === 1
            ) {
                return $next($request);
            }
        }

        return abort(404);
    }

    protected function authenticatedUsersProfile() {
        return auth()->id() === (int) request()->query('user') && auth()->user()->active === 1;
    }
}
