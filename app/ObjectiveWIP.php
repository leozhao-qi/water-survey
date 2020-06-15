<?php

namespace App;

use App\LessonWIP;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ObjectiveWIP extends Model
{
    use HasTranslations;
    
    protected $connection = 'mysql';

    protected $table = 'objectives_wip';

    public $translatable = [ 'name' ];

    protected $fillable = [
        'lesson_id',
        'number',
        'name',
        'type'
    ];

    public function lesson()
    {
    	return $this->belongsTo(LessonWIP::class, 'lesson_id', 'id');
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
