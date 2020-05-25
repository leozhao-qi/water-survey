<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class IsAuthorizedToSignOff implements Rule
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (auth()->user()->hasRole('administrator')) {
            return true;
        }

        $isAuthorized = $this->user
            ->reportingStructure()
            ->flatten(1)
            ->where('id', auth()->id())
            ->whereIn('role', ['manager', 'head_of_operations'])
            ->count();
        
        if ($isAuthorized === 0) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You are not approved to sign off on this lesson package.';
    }
}
