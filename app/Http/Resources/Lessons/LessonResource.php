<?php

namespace App\Http\Resources\Lessons;

use App\LessonVersion;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name_en' => $this->getTranslation('name', 'en'),
            'name_fr' => $this->getTranslation('name', 'fr'),
            'name' => $this->name,
            'formatNumber' => $this->topic_id ? $this->topic->number . '.' . str_pad($this->number, 2, '0', STR_PAD_LEFT) : 'No topic.' . $this->number,
            'number' => $this->number,
            'version' => LessonVersion::find($this->lesson_version_id)->version,
            'level_id' => $this->level_id,
            'completed_in_both' => $this->completed_in_both ? true : false,
            'topic_id' => $this->topic_id
        ];
    }
}
