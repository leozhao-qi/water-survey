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
            'lesson' => $this->lesson->number,
            'lesson_id' => $this->lesson_id,
            'number' => $this->number,
            'name' => $this->name,
            'type_format' => ucfirst(str_replace('_', ' ', $this->type)),
            'type' => $this->type,
            'name_en' => $this->getTranslation('name', 'en'),
            'name_fr' => $this->getTranslation('name', 'fr'),
        ];
    }
}
