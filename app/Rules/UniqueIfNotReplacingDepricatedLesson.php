<?php

namespace App\Rules;

use App\Lesson;
use Illuminate\Contracts\Validation\Rule;

class UniqueIfNotReplacingDepricatedLesson implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (request('replaces')) {
            return true;
        }

        $lessonCount = Lesson::whereNumber(request('number'))->get()->count();

        return $lessonCount === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The lesson number must be unique.';
    }
}
