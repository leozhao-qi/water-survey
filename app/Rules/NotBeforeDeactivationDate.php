<?php

namespace App\Rules;

use App\User;
use Carbon\Carbon;
use App\Deactivation;
use Illuminate\Contracts\Validation\Rule;

class NotBeforeDeactivationDate implements Rule
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
        $date = Carbon::parse($value);

        $deactivation = Deactivation::where('user_id', $this->user->id)->latest()->first();

        return $date->isAfter($deactivation->deactivated_at);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This date is before the deactivation date.';
    }
}
