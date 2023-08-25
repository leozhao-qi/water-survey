<?php

namespace App\Rules;

use App\LessonWIP;
use Illuminate\Contracts\Validation\Rule;

class UniqueLessonWIP implements Rule
{
    protected $lesson;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(LessonWIP $lesson)
    {
        $this->lesson = $lesson;
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
        if (request('number') === $this->lesson->number) {
            return true;
        }

        return !LessonWIP::whereNumber(request('number'))
            ->whereTopicId((int) request('topic_id'))
            ->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This lesson number already exists.';
    }
}
