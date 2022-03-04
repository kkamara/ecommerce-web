<?php

namespace App\Repositories\Product;

use App\Models\Product\Product;
use App\Http\Resources\Product\ProductCollection;

class ProductRepository implements IProductRepository
{
    /**
     * @param [query,sort_by,min_price,max_price][] $params
     * @return \Illuminate\Http\Response
     */
    public function search($params): ProductCollection
    {
        return new ProductCollection(Product::getProducts(
            $params['query'] ?? '',
            $params['sort_by'] ?? '',
            $params['min_price'] ?? 0,
            $params['max_price'] ?? 0,
        )->paginate(20));
    }
}
