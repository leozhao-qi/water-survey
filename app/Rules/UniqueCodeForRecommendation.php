<?php

namespace App\Rules;

use App\Recommendation;
use Illuminate\Contracts\Validation\Rule;

class UniqueCodeForRecommendation implements Rule
{
    protected $recommendation;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Recommendation $recommendation)
    {
        $this->recommendation = $recommendation;
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
        if (request('code') === $this->recommendation->code) {
            return true;
        }

        return !Recommendation::whereCode($this->recommendation->code)
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
