<?php

namespace App\Http\Resources\LogbookCategories;

use Illuminate\Http\Resources\Json\JsonResource;

class LogbookCategoryResource extends JsonResource
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
            'name' => $this->name,
            'supervisor_only' => $this->supervisor_only ? true : false,
            'supervisor_only_formatted' => $this->supervisor_only ? 'True' : 'False',
            'name_en' => $this->getTranslation('name', 'en'),
            'name_fr' => $this->getTranslation('name', 'fr')
        ];
    }
}
