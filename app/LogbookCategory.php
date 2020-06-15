<?php

namespace App;

use App\Logbook;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LogbookCategory extends Model
{
    use HasTranslations;
    
    protected $fillable = [
        'name',
        'supervisor_only'
    ];

    public $translatable = ['name'];

    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
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
