<?php

namespace App\Repositories\Product;

use App\Http\Resources\Product\ProductCollection;

interface IProductRepository 
{
    public function search(\Illuminate\Support\Collection $request): ProductCollection;
}
