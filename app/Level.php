<?php

namespace App;

use App\Lesson;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Level extends Model
{
    use HasTranslations;

    public $translatable = ['name'];
    
    protected $fillable = ['name'];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
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
