<?php

namespace App;

use App\LogbookCategory;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $fillable = [
        'user_id',
        'logbook_category_id',
        'created',
        'details_of_event',
        'event_description'
    ];

    public function logbookCategory()
    {
        return $this->belongsTo(LogbookCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
