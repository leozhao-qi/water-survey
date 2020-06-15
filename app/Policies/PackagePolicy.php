<?php

namespace App\Policies;

use App\User;
use App\Package;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagePolicy
{
    use HandlesAuthorization;

    protected $package;

    protected $authArr = [
        'complete' => [ 'administrator', 'manager', 'head_of_operations' ],
        'theory_status' => [ 'administrator', 'manager', 'head_of_operations', 'supervisor' ],
        'practical_status' => [ 'administrator', 'manager', 'head_of_operations', 'supervisor' ],
        'objectives' => [ 'administrator', 'manager', 'head_of_operations', 'supervisor' ],
        'recommendation_id' => [ 'administrator', 'manager', 'head_of_operations', 'supervisor' ],
        'comment' => [ 'administrator', 'manager', 'head_of_operations' ],
        'evaluation_details' => [ 'administrator', 'manager', 'head_of_operations', 'supervisor' ],
        'recommendation_comment' => [ 'administrator', 'manager', 'head_of_operations', 'supervisor' ]
    ];

    public function __construct()
    {
        preg_match_all("/\/users\/([\d]+)/",request()->url(),$matches);

    	$this->user = User::find((int) $matches[1][0]);
    }

    public function update(User $authUser, Package $package)
    {
        if (auth()->user()->hasRole(['apprentice'])) {
            return false;
        }
        
        return $this->validations();
    }

    protected function validations()
    {
        $userRole = auth()->user()->roles->first()->name;

        foreach (array_keys(request()->all()) as $item) {
            if (array_search($userRole, $this->authArr[$item]) === false) {
                 return false;
            }

            continue;
        }

        return true;
    }
}
