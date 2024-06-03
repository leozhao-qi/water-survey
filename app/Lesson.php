<?php

namespace App;

use App\Level;
use App\Topic;
use App\Package;
use App\Objective;
use App\LessonVersion;
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
        'lesson_version_id',
        'completed_in_both',
        'topic_id'
    ];

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function objectives()
    {
        return $this->hasMany(Objective::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function lessonVersion()
    {
        return $this->belongsTo(LessonVersion::class);
    }

    public function toArray(): array
    {
        $attributes = parent::toArray();

        foreach ($this->getTranslatableAttributes() as $name) {
            $attributes[$name] = $this->getTranslation($name, app()->getLocale());
        }

        return $attributes;
    }

    /**
     * Returns lesson number as padded alphanumeric, for example "05A"
     *
     * @return string
     */
    public function formatNumber(): string
    {
        return str_pad($this->number, 2, 0, STR_PAD_LEFT);
    }
}
