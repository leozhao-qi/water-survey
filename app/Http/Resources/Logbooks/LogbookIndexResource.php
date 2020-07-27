<?php

namespace App\Http\Resources\Logbooks;

use App\User;
use App\Package;
use App\LogbookCategory;
use Illuminate\Support\Str;
use App\Http\Resources\Comments\CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LogbookCategories\LogbookCategoryResource;

class LogbookIndexResource extends JsonResource
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
            'details_of_event_truncated' => Str::limit(strip_tags($this->details_of_event), 256) . '...',
            'category' => new LogbookCategoryResource(
                $this->logbookCategory
            ),
            'created' => $this->created,
            'references' => $this->references ? User::find($this->references) : null,
            'user' => $this->user,
            'files' => $this->logbookFiles->count(),
            'updated' => !$this->created_at->equalTo($this->updated_at) ? $this->updated_at : null,
            'comments' => $this->comments->count(),
            'packages' => implode(', ', $this->logbookPackages->map(function ($p) {
                $package = Package::find($p['package_id']);

                return [
                    'number' => $package->lesson->topic->number . '.' . str_pad($package->lesson->number, 2, '0', STR_PAD_LEFT)
                ];
            })->sortBy('number')->pluck('number')->toArray())
        ];
    }
}
