<?php

namespace App\Http\Resources\Logbooks;

use App\User;
use App\Package;
use App\LessonVersion;
use App\Http\Resources\Comments\CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LogbookCategories\LogbookCategoryResource;

class LogbookShowResource extends JsonResource
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
            'event_description' => $this->event_description,
            'details_of_event' => $this->details_of_event,
            'logbook_category_id' => $this->logbook_category_id,
            'category' => new LogbookCategoryResource(
                $this->logbookCategory
            ),
            'created' => $this->created,
            'user' => $this->user,
            'files' => $this->logbookFiles,
            'updated' => !$this->created_at->equalTo($this->updated_at) ? $this->updated_at : null,
            'comments' => CommentResource::collection(
                $this->comments()
                    ->with(['user', 'commentable'])
                    ->get()
            ),
            'packages' => $this->logbookPackages->pluck('package_id')->toArray(),
            'packagesArr' => $this->logbookPackages->map(function ($p) {
                $package = Package::find($p['package_id']);

                return [
                    'id' => $p['package_id'],
                    'lesson' => $package->lesson->number . ' - ' . $package->lesson->name,
                    'formatNumber' => $package->lesson->topic_id ? 
                    $package->lesson->topic->number . '.' . $package->lesson->formatNumber() : 
                    'No topic.' . $package->lesson->formatNumber(),
                    'versionNumber' => LessonVersion::find($package->lesson->lesson_version_id)->version,
                    'lessonName' => $package->lesson->name,
                    'complete' => $package->complete
                ];
            })->sortBy('formatName')->toArray(),
            'references' => $this->references ? User::find($this->references) : null,
        ];
    }
}
