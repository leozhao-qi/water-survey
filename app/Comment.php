<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
    	'body'
    ];

    protected $dates = [
        'edited_at'
    ];

    protected $with = [
        'user'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function commentable()
    {
    	return $this->morphTo();
    }
}
