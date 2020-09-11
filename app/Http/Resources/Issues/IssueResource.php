<?php

namespace App\Http\Resources\Issues;

use Illuminate\Http\Resources\Json\JsonResource;

class IssueResource extends JsonResource
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
            'issuer_id' => $this->issuer_id,
            'issuer' => $this->issuer->fullname,
            'title' => $this->title,
            'body' => $this->body,
            'closed' => $this->closed ? 'Yes' : 'No',
            'closed_at' => $this->closed_at ? $this->closed_at->format('m/d/y') : 'Open',
            'status' => $this->status,
            'code' => $this->code
        ];
    }
}
