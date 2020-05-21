<?php

namespace App\Observers;

use App\LessonWIP;

class LessonWIPObserver
{
    /**
     * Handle the lesson "deleted" event.
     *
     * @param  \App\LessonWIP  $lesson
     * @return void
     */
    public function deleted(LessonWIP $lesson)
    {
        $lesson->objectives->each->delete();
    }
}
