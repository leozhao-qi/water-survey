<?php

namespace App\Rules;

use App\ObjectiveWIP;
use Illuminate\Contracts\Validation\Rule;

class UniqueObjectiveNumberForLessonWIP implements Rule
{
    protected $objective;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(ObjectiveWIP $objective)
    {
        $this->objective = $objective;
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
        if (request('number') === $this->objective->number) {
            return true;
        }

        return !ObjectiveWIP::whereLessonId($this->objective->lesson_id)
            ->whereNumber($this->objective->number)
            ->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This number already exists for this lesson.';
    }
}
