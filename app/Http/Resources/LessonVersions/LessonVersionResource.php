<?php

namespace App\Http\Resources\LessonVersions;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonVersionResource extends JsonResource
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
            'version' => $this->version,
            'valid_on' => $this->valid_on,
            'valid_on_formatted' => $this->valid_on->format('m/d/yy')
        ];
    }
}
