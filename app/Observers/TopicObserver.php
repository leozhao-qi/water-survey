<?php

namespace App\Observers;

use App\Topic;

class TopicObserver
{
    /**
     * Handle the topic "deleted" event.
     *
     * @param  \App\Topic  $topic
     * @return void
     */
    public function deleted(Topic $topic)
    {
        $topic->lessons->each->update([
            'topic_id' => null
        ]);
    }
}
