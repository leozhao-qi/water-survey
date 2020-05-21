<?php

namespace App\Observers;

use App\Lesson;

class LessonObserver
{
    /**
     * Handle the lesson "deleted" event.
     *
     * @param  \App\Lesson  $lesson
     * @return void
     */
    public function deleted(Lesson $lesson)
    {
        $lesson->objectives->each->delete();

        $lesson->packages->each->delete();
    }
}
