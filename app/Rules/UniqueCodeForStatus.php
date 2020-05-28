<?php

namespace App\Rules;

use App\Status;
use Illuminate\Contracts\Validation\Rule;

class UniqueCodeForStatus implements Rule
{
    protected $status;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Status $status)
    {
        $this->status = $status;
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
        if (request('code') === $this->status->code) {
            return true;
        }

        return !Status::whereCode($this->status->code)
            ->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This code already exists.';
    }
}
