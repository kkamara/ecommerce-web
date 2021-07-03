<?php

namespace App\Models\Product\Traits;

use Illuminate\Support\Facades\DB;

trait ProductScopes {
    /**
     * Query products using request params.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $query
     * @param  \Illuminate\Http\Request             $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scopeGetProducts($query, $request)
    {
        $querySearch = $request->input('query');
        $sort_by     = $request->input('sort_by');

        $min = is_numeric($request->input('min_price'))
            ? ((float) $request->input('min_price') * 100)
            : null;
        $max = is_numeric($request->input('max_price'))
            ? ((float) $request->input('max_price') * 100)
            : null;
        
        $query->select('products.id', 'products.name', 'products.user_id', 'products.company_id',
                       'products.short_description', 'products.long_description', 'products.product_details',
                       'products.image_path', 'products.cost', 'products.shippable', 'products.free_delivery',
                       'products.created_at', 'products.updated_at',
                       'order_history_products.product_id',);
        $whereClause = array();

        if(isset($querySearch))
        {
            $querySearch = filter_var($querySearch, FILTER_SANITIZE_STRING);
            array_push($whereClause, [
                'products.name', 'LIKE', "$querySearch%"
            ]);
        }
        if(isset($min))
        {
            $min = filter_var($min, FILTER_SANITIZE_NUMBER_FLOAT);
            array_push($whereClause, [
                'products.cost', '>', $min
            ]);
        }
        if(isset($max))
        {
            $max = filter_var($max, FILTER_SANITIZE_NUMBER_FLOAT);
            array_push($whereClause, [
                'products.cost', '<', $max
            ]);
        }

        if(isset($whereClause)) {
            $query->where($whereClause);
        }

        $query->leftJoin(
            'order_history_products', 
            'products.id', 
            '=', 
            'order_history_products.product_id'
        );

        switch($sort_by)
        {
            case 'pop': // most popular
                return $query
                    ->where('order_history_products.product_id', '!=', null)
                    ->groupBy('order_history_products.product_id')
                    ->orderBy(DB::raw('count(order_history_products.product_id)'), 'DESC');
            case 'top': // top rated
                return $query->leftJoin(
                        'product_reviews', 
                        'products.id', 
                        '=', 
                        'product_reviews.product_id'
                    )
                    ->withCount([
                        'productReview as review' => function($query) {
                            $query->select(
                                DB::raw('avg(product_reviews.score) as average_rating')
                            );
                        }
                    ])
                    ->groupBy('product_reviews.product_id')
                    ->orderByDesc('review')
                    ->orderBy('products.id', 'DESC')
                    ->groupBy('products.id')
                    ->distinct();
            case 'low': // lowest price
                return $query->orderBy('cost', 'ASC')
                    ->orderBy('products.id', 'DESC')
                    ->groupBy('products.id')
                    ->distinct();
            case 'hig': // highest price
                return $query->orderBy('cost', 'DESC')
                    ->orderBy('products.id', 'DESC')
                    ->groupBy('products.id')
                    ->distinct();
            default:
                return $query
                    ->orderBy('products.id', 'DESC')
                    ->groupBy('products.id')
                    ->distinct();
        }
    }

    /**
     * Get products that belong to a given vendor.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $query
     * @param  \App\Models\Company\Company          $companyId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scopeGetCompanyProducts($query, $companyId)
    {
        return $query->where('company_id', '=', $companyId);
    }
}
