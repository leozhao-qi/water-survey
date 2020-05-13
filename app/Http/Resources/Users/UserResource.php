<?php

namespace App\Http\Resources\Users;

use App\Package;
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
                Package::whereUserId($this->id)->get()
            )
        ];
    }
}
