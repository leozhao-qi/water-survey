<?php

namespace App;

use App\Lesson;
use Illuminate\Database\Eloquent\Model;

class LessonVersion extends Model
{
    protected $fillable = [
        'version',
        'valid_on'
    ];

    protected $casts = [
        'valid_on' => 'date'
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
