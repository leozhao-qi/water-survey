<?php

namespace App\Http\Resources\Sot;

use Illuminate\Http\Resources\Json\JsonResource;

class SotPackageResource extends JsonResource
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
            'topic_number' => $this->lesson->topic->number . '.00'
        ];
    }
}
