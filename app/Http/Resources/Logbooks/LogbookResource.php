<?php

namespace App\Http\Resources\Logbooks;

use App\LogbookCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\LogbookCategories\LogbookCategoryResource;

class LogbookResource extends JsonResource
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
            'details_of_event' => $this->details_of_event,
            'logbook_category_id' => $this->logbook_category_id,
            'category' => new LogbookCategoryResource(
                $this->logbookCategory
            ),
            'created' => $this->created,
            'user' => $this->user,
            'files' => $this->logbookFiles
        ];
    }
}
