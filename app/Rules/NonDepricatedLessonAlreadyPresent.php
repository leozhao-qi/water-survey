<?php

namespace App\Rules;

use App\Lesson;
use Illuminate\Contracts\Validation\Rule;

class NonDepricatedLessonAlreadyPresent implements Rule
{
    protected $lesson;

    public function __construct(Lesson $lesson)
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
        if (request('depricated') === 1) {
            return true;
        }

        $lessonCount = Lesson::whereNumber(request('number'))
            ->whereDepricated(0)
            ->get()
            ->count();

        if ($lessonCount && $this->lesson->depricated === 1) {
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
        return 'There is already a non-depricated lesson of this number.';
    }
}
