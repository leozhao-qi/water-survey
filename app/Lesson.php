<?php

namespace App;

use App\Level;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Lesson extends Model
{
    use HasTranslations;

    public $translatable = ['name'];
    
    protected $fillable = [
        'level_id',
        'number',
        'name',
        'depricated'
    ];

    public function level ()
    {
        return $this->belongsTo(Level::class);
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
