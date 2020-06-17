<?php

namespace App\Rules;

use App\Topic;
use Illuminate\Contracts\Validation\Rule;

class UniqueNumberForTopic implements Rule
{
    protected $topic;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
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
        if ((int) request('number') === $this->topic->number) {
            return true;
        }

        return !Topic::whereNumber($this->topic->number)
            ->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This topic number already exists.';
    }
}
