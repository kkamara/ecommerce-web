<?php

namespace App\Models\User\Traits;

trait UserRelations {
    /**
     * This model relationship has many \App\Models\Product\ProductReview.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function productReview()
    {
        return $this->hasMany('App\Models\Product\ProductReview', 'user_id');
    }

    /**
     * This model relationship has many \App\Models\User\UserPaymentConfig.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function userPaymentConfig()
    {
        return $this->hasMany('App\Models\User\UserPaymentConfig', 'user_id');
    }

    /**
     * This model relationship has many \App\Models\User\UsersAddress.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function userAddress()
    {
        return $this->hasMany('App\Models\User\UsersAddress', 'user_id');
    }

    /**
     * This model relationship has many \App\Models\Company\VendorApplication.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function vendorApplication()
    {
        return $this->hasOne('App\Models\Company\VendorApplication');
    }


    /**
     * This model relationship has many \App\Models\Cart\Cart.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function cart()
    {
        return $this->hasMany('App\Models\Cart\Cart', 'user_id');
    }

    /**
     * This model relationship has one \App\Models\Company\Company.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function company()
    {
        return $this->hasOne('App\Models\Company\Company', 'user_id');
    }

    /**
     * This model relationship has many \App\Models\Order\OrderHistory.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function orderHistory()
    {
        return $this->hasMany('App\Models\Order\OrderHistory', 'user_id');
    }

    /**
     * This model relationship has many \App\Models\Product\Product.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function product()
    {
        return $this->hasMany('App\Models\Product\Product', 'user_id');
    }
}
