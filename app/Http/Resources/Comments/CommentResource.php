<?php

namespace App\Http\Resources\Comments;

use App\Http\Resources\Users\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'body' => $this->body,
            'owner' => 
            	auth()->user()->id === $this->user_id
            	|| 
            	auth()->user()->hasRole(['administrator']),
            'user' => new UserResource($this->user),
            'created_at' => $this->created_at->format('m/d/Y'),
            'edited' => optional($this->edited_at)->format('m/d/Y')
        ];
    }
}
