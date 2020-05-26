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
            'version' => LessonVersion::find($this->lesson->lesson_version_id)->version,
            'signed_off_by' => $this->signed_off_by ? User::find($this->signed_off_by) : null,
            'signed_off_at' => $this->signed_off_at,
            'practical_status' => $this->practical_status,
            'theory_status' => $this->theory_status,
            'complete' => $this->complete,
            'objective_types' => $this->lesson->objectives->pluck('type')->filter()->unique()->toArray()
        ];
    }
}
