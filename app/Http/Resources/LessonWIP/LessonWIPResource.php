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
        if ($this->topic_id) {
            if (strlen($this->number) === 2 && !is_numeric($this->number)) {
                $formatNumber = $this->topic->number . '.' . str_pad($this->number, 3, '0', STR_PAD_LEFT);
            } else {
                $formatNumber = $this->topic->number . '.' . str_pad($this->number, 2, '0', STR_PAD_LEFT);
            }
        } else {
            $formatNumber = 'No topic.' . $this->number;
        }
        
        return [
            'id' => $this->id,
            'name_en' => $this->getTranslation('name', 'en'),
            'name_fr' => $this->getTranslation('name', 'fr'),
            'name' => $this->name,
            'formatNumber' => $formatNumber,
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
