<?php

namespace App;

use App\ObjectiveWIP;
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
        'name',
        'completed_in_both'
    ];

    public function level ()
    {
        return $this->belongsTo(Level::class);
    }

    public function objectives()
    {
        return $this->hasMany(ObjectiveWIP::class, 'lesson_id', 'id');
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
