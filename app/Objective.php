<?php

namespace App;

use App\Lesson;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Objective extends Model
{
    use HasTranslations;

    protected $translatable = ['name'];

    protected $fillable = [
        'lesson_id', 
        'name', 
        'number',
        'type'
    ];

    public function lesson()
    {
    	return $this->belongsTo(Lesson::class);
    }

    public function toArray()
    {
        $attributes = parent::toArray();
        
        foreach ($this->getTranslatableAttributes() as $name) {
            $attributes[$name] = $this->getTranslation($name, app()->getLocale());
        }
        
        return $attributes;
    }
}
