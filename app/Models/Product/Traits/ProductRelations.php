<?php

namespace App\Models\Product\Traits;

trait ProductRelations {
    /**
     * This model relationship belongs to \App\Models\Company\Company.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company\Company', 'company_id');
    }

    /**
     * This model relationship has many to \App\Models\Product\ProductReview.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function productReview()
    {
        return $this->hasMany('App\Models\Product\ProductReview', 'product_id');
    }

    /**
     * This model relationship has many to \App\Models\Order\OrderHistoryProducts.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function orderHistoryProducts()
    {
        return $this->hasMany('App\Models\Order\OrderHistoryProducts', 'product_id');
    }

    /**
     * This model relationship belongs to \App\Models\User.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
