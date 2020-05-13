<?php

namespace App;

use App\Level;
use App\Package;
use App\Objective;
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

    public function objectives()
    {
        return $this->hasMany(Objective::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
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
