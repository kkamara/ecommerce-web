<?php

namespace App\Models\Product\Traits;

use Illuminate\Support\Facades\DB;

trait ProductScopes {
    /**
     * Query products using request params.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $query
     * @param  String                               $rQuery
     * @param  String                               $rSortBy,
     * @param  Float                                $rMinPrice,
     * @param  Float                                $rMaxPrice,
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scopeGetProducts($query, $rQuery='', $rSortBy='', $rMinPrice=0, $rMaxPrice=0,) {
        $querySearch = $rQuery;
        $sort_by     = $rSortBy;

        $min = is_numeric($rMinPrice) ? ((float) $rMinPrice * 100) : 0;
        $max = is_numeric($rMaxPrice) ? ((float) $rMaxPrice * 100) : 0;
        
        $query->select(
            'products.id', 'products.slug', 'products.name', 'products.user_id', 'products.company_id',
            'products.short_description', 'products.long_description', 'products.product_details',
            'products.image_path', 'products.cost', 'products.shippable', 'products.free_delivery',
            'products.created_at', 'products.updated_at', 'order_history_products.product_id',
        );

        /**
         * Prepare request parameters for db query
         * @var Array
         */
        $whereClause = array();

        if(isset($querySearch))
        {
            $querySearch = filter_var($querySearch, FILTER_SANITIZE_STRING);
            array_push($whereClause, ['products.name', 'LIKE', "%$querySearch%",]);
        }
        if(0 != $min)
        {
            $min = filter_var($min, FILTER_SANITIZE_NUMBER_FLOAT);
            array_push($whereClause, ['products.cost', '>', $min,]);
        }
        if(0 != $max)
        {
            $max = filter_var($max, FILTER_SANITIZE_NUMBER_FLOAT);
            array_push($whereClause, ['products.cost', '<', $max,]);
        }

        $query->when(
            $whereClause, 
            fn ($query, $whereClause) => $query->where($whereClause),
        );

        $query->leftJoin(
            'order_history_products', 
            'products.id', 
            '=',
            'order_history_products.product_id',
        );

        return match($sort_by) {
            // most popular
            'pop' => $query->where(
                    'order_history_products.product_id', 
                    '!=', 
                    null,
                )
                ->groupBy('order_history_products.product_id')
                ->orderByDesc(DB::raw('count(order_history_products.product_id)')),
            // top rated
            'top' => $query->leftJoin(
                    'product_reviews', 
                    'products.id', 
                    '=', 
                    'product_reviews.product_id',
                )
                ->withCount([
                    'productReview as review' => fn ($query) => $query->select(
                        DB::raw('avg(product_reviews.score) as average_rating')
                    ),
                ])
                ->groupBy('product_reviews.product_id')
                ->orderByDesc('review')
                ->orderByDesc('products.id')
                ->groupBy('products.id')
                ->distinct(),
            // lowest price
            'low' => $query->orderByAsc('cost')
                ->orderByDesc('products.id')
                ->groupBy('products.id')
                ->distinct(),
            // highest price
            'hig' => $query->orderByDesc('cost')
                ->orderByDesc('products.id')
                ->groupBy('products.id')
                ->distinct(),
            default => $query->orderByDesc('products.id')
                ->groupBy('products.id')
                ->distinct(),
        };
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

    /**
     * Find whether a given user has purchased this product instance.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $query
     * @param  \App\Models\User                     $user
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scopeBoughtBy($query, $user)
    {
        return $query
            ->leftJoin('order_history_products', 'products.id', '=','order_history_products.product_id',)
            ->leftJoin('order_history', 'order_history_products.order_history_id', '=', 'order_history.id',)
            ->leftJoin('users', 'order_history.user_id', '=', 'users.id',)
            ->where(
                'users.email', 
                '=',
                $user['email'],
            );
    }
}
