<?php

namespace App\Http\Resources\Users;

use App\Lesson;
use App\Package;
use App\LessonVersion;
use App\Http\Resources\Packages\PackageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $lessonVersion = LessonVersion::find(
            $this->packages->first()->lesson->lesson_version_id
        );
        $packageVersion = $lessonVersion->version;

        $assignedLessons = Package::whereUserId($this->id)->get()->pluck('lesson_id')->toArray();
        $lesonsOfVersion = Lesson::whereLessonVersionId($lessonVersion->id)->get()->pluck('id')->toArray();

        $unassignedLessons = Lesson::whereIn('id', array_diff($lesonsOfVersion, $assignedLessons))->get();

        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'deactivations' => $this->deactivations,
            'active' => $this->active,
            'role' => $this->roles->first()->name,
            'roleRank' => $this->roles->first()->rank,
            'reportingStructure' => $this->reportingStructure(),
            'appointment_date' => $this->appointment_date,
            'packages' => PackageResource::collection(
                $this->packages
            ),
            'packageVersion' => $packageVersion,
            'unassignedLessons' => $unassignedLessons,
        ];
    }
}
