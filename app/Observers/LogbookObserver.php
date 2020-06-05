<?php

namespace App\Observers;

use App\Logbook;

class LogbookObserver
{
    /**
     * Handle the logbook "deleted" event.
     *
     * @param  \App\Logbook  $logbook
     * @return void
     */
    public function deleted(Logbook $logbook)
    {
        // Delete logbook file attachments
        foreach ($logbook->logbookFiles as $file) {
            $pathToFile = "/public/entries/" . auth()->id() . "/{$file->coded_filename}";

            Storage::delete($pathToFile);

            $file->delete();
        }
    }
}
