<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $permissionToReview = FALSE;

        if($user = \App\User::attemptAuth()) {
            $permissionToReview = $this->didUserPurchaseProduct($user->id);
        }

        return array_merge(
            $this->attributesToArray(),
            [ "reviews" => $this->productReview()->get() ],
            compact('permissionToReview'),
        );
    }
}
