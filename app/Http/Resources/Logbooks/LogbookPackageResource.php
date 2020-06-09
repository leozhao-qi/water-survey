<?php

namespace App\Http\Resources\Logbooks;

use Illuminate\Http\Resources\Json\JsonResource;

class LogbookPackageResource extends JsonResource
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
            'lesson' => $this->lesson->number . ' - ' . $this->lesson->name,
            'complete' => $this->complete
        ];
    }
}
