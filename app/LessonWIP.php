<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LessonWIP extends Model
{
    use HasTranslations;
    
    protected $connection = 'mysql';

    protected $table = 'lessons_wip';

    public $translatable = [ 'name' ];

    protected $fillable = [
        'level_id',
        'number',
        'name'
    ];

    public function toArray()
    {
        $attributes = parent::toArray();
        
        foreach ($this->getTranslatableAttributes() as $name) {
            $attributes[$name] = $this->getTranslation($name, app()->getLocale());
        }
        
        return $attributes;
    }
}
