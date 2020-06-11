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
            'number' => $this->number,
            'version' => LessonVersion::find($this->lesson_version_id)->version,
            'level_id' => $this->level_id,
            'completed_in_both' => $this->completed_in_both ? true : false
        ];
    }
}
