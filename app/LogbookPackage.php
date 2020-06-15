<?php

namespace App;

use App\Logbook;
use Illuminate\Database\Eloquent\Model;

class LogbookPackage extends Model
{
    protected $fillable = [
        'logbook_id',
        'package_id'
    ];

    public function logbook()
    {
        return $this->belongsTo(Logbook::class);
    }
}
