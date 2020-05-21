<?php

namespace App\Http\Resources\Packages;

use App\User;
use App\LessonVersion;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'level' => $this->lesson->level->name,
            'package' => $this->lesson->number . ' - ' . $this->lesson->name,
            'user' => User::find($this->user_id),
            'user_id' => $this->user_id,
            'lesson_id' => $this->lesson_id,
            'version' => LessonVersion::find($this->lesson->lesson_version_id)->version
        ];
    }
}
