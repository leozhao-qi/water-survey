<?php

namespace App\Http\Resources\LessonWIP;

use App\ObjectiveWIP;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ObjectiveWIP\ObjectiveWIPResource;

class LessonWIPResource extends JsonResource
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
            'level_id' => $this->level_id,
            'objectives' => ObjectiveWIPResource::collection(
                ObjectiveWIP::whereLessonId($this->id)->get()
            )
        ];
    }
}
