<?php

namespace App\Http\Resources\Company;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'mobile_number' => $this->mobile_number,
            'mobile_number_extension' => $this->mobile_number_extension,
            'phone_number' => $this->phone_number,
            'phone_number_extension' => $this->phone_number_extension,
            'building_name' => $this->building_name,
            'street_address1' => $this->street_address1,
            'street_address2' => $this->street_address2,
            'street_address3' => $this->street_address3,
            'street_address4' => $this->street_address4,
            'county' => $this->county,
            'city' => $this->city,
            'country' => $this->country,
            'postcode' => $this->postcode,
            'user' => new UserResource($this->user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
