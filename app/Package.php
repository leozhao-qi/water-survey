<?php

namespace App;

use App\User;
use App\Lesson;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'lesson_id',
        'user_id',
        'complete',
        'signed_off_by',
        'signed_off_at',
        'theory_status',
        'practical_status',
        'recommendation_id',
        'recommendation_comment',
        'recommendation_comment_by',
        'recommendation_comment_at',
        'comment',
        'evaluation_details',
        'commented_by',
        'commented_at',
        'evaluated_by',
        'evaluated_at'
    ];

    protected $casts = [
        'signed_off_at' => 'date',
        'commented_at' => 'date',
        'evaluated_at' => 'date',
        'recommendation_comment_at' => 'date'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function lesson()
    {
    	return $this->belongsTo(Lesson::class);
    }

    public function recommendation()
    {
    	return $this->belongsTo(Recommendation::class);
    }
}
