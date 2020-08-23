<?php

namespace App\Http\Resources;

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
        return array_merge(
            $this->attributesToArray(),
            [
                "billingCards" => $this->userPaymentConfig->all(),
                "addresses" => $this->userAddress->all(),
                "company" => $this->company_name,
                "role" => $this->role_name,
            ],
        );
    }
}
