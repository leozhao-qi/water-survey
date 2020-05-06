<?php

namespace App\Rules;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class NotBeforeAppointmentDate implements Rule
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
        if ($this->user->appointment_date === null) {
            return true;
        }

        $date = Carbon::parse($value);

        return $date->isAfter($this->user->appointment_date);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This date is before ' . $this->user->firstname . ' ' . $this->user->lastname . '\'s appointment date';
    }
}
