<?php

namespace App\Http\Resources\Objectives;

use Illuminate\Http\Resources\Json\JsonResource;

class ObjectiveResource extends JsonResource
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
            'lesson' => $this->lesson->topic_id ? $this->lesson->topic->number . '.' . str_pad($this->lesson->number, 2, '0', STR_PAD_LEFT) : 'No topic.' . $this->lesson->number,
            'lesson_id' => $this->lesson_id,
            'lesson_version' => $this->lesson->lessonVersion->version,
            'number' => $this->number,
            'name' => $this->name,
            'type_format' => ucfirst(str_replace('_', ' ', $this->type)),
            'type' => $this->type,
            'name_en' => $this->getTranslation('name', 'en'),
            'name_fr' => $this->getTranslation('name', 'fr')
        ];
    }
}
