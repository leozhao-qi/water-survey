<?php

namespace App\Rules;

use App\Lesson;
use Illuminate\Contracts\Validation\Rule;

class UniqueLessonForVersion implements Rule
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
        if (!request('lesson_version_id')) {
            return !Lesson::whereNumber(request('number'))->count();
        }

        return !Lesson::whereNumber(request('number'))
            ->whereLessonVersionId(request('lesson_version_id'))
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
