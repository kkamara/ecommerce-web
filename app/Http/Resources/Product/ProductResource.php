<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Company\CompanyResource;

class ProductResource extends JsonResource
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
            'formatted_cost' => $this->formatted_cost,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'product_details' => $this->product_details,
            'image_path' => $this->image_path,
            'cost' => $this->cost,
            'shippable' => $this->shippable,
            'free_delivery' => $this->free_delivery,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'review' => $this->review,
            'company' => new CompanyResource($this->company),
        ];
    }
}
