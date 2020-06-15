<?php

namespace App\Observers;

use App\Level;

class LevelObserver
{
    /**
     * Handle the level "deleted" event.
     *
     * @param  \App\Level  $level
     * @return void
     */
    public function deleted(Level $level)
    {
        // Delete all associated lessons
        $level->lessons->each->delete();
    }
}
