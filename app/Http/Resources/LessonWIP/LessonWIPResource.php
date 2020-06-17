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
            'formatNumber' => $this->topic_id ? $this->topic->number . '.' . str_pad($this->number, 2, '0', STR_PAD_LEFT) : 'No topic.' . $this->number,
            'number' => $this->number,
            'level_id' => $this->level_id,
            'level' => $this->level,
            'objectives' => ObjectiveWIPResource::collection(
                ObjectiveWIP::whereLessonId($this->id)->get()
            ),
            'completed_in_both' => $this->completed_in_both ? true : false,
            'topic_id' => $this->topic_id,
            'topic' => $this->topic_id ? $this->topic->number . ' - ' . $this->topic->name : 'No topic'
        ];
    }
}
