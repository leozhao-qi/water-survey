<?php

namespace App\Rules;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidDeactivation implements Rule
{
    protected $user;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
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
        if (count($this->user->deactivations) === 0) {
            return true;
        }

        foreach ($this->user->deactivations as $deactivation) {
            $date = Carbon::parse($value);
            
            if ($date->between($deactivation->deactivated_at, $deactivation->reactivated_at, true) === true) {
                return false;
            }
            
            continue;
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
        return 'This date falls within another deactivation';
    }
}
