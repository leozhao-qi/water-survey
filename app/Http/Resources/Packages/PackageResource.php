<?php

namespace App\Http\Resources\Packages;

use App\User;
use App\LessonVersion;
use Illuminate\Support\Arr;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Recommendations\RecommendationResource;

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
        $objectivesArr = $this->lesson->objectives->map(function ($objective) {
            if ($objective['type'] === null) {
                $objective['type'] = 'not_defined';
            }

            return $objective;
        })->groupBy('type')->toArray();

        $completedObjectives = $this->user->objectives->whereIn(
            'id', $this->lesson->objectives->pluck('id')
        )->pluck('id');

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
            'commented_by' => $this->commented_by ? User::find($this->commented_by) : null,
            'commented_at' => $this->commented_at,
            'evaluated_by' => $this->evaluated_by ? User::find($this->evaluated_by) : null,
            'evaluated_at' => $this->evaluated_at,
            'practical_status' => $this->practical_status,
            'theory_status' => $this->theory_status,
            'complete' => $this->complete,
            'objective_types' => $this->lesson->objectives->pluck('type')->filter()->unique()->toArray(),
            'objectives' => $objectivesArr,
            'completedObjectives' => count($completedObjectives) ? $completedObjectives : [],
            'recommendation' => new RecommendationResource($this->recommendation),
            'recommendation_comment' => $this->recommendation_comment,
            'recommendation_comment_by' => $this->recommendation_comment_by ? User::find($this->recommendation_comment_by) : null,
            'recommendation_comment_at' => $this->recommendation_comment_at,
            'comment' => $this->comment,
            'evaluation_details' => $this->evaluation_details
        ];
    }
}
