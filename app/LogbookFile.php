<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogbookFile extends Model
{
    protected $fillable = [
        'logbook_id',
        'actual_filename',
        'coded_filename'
    ];

    public function logbook()
    {
        return $this->belongsTo(Logbook::class);
    }
}
