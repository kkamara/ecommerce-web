<?php

namespace App\Models\Order\Traits;

trait OrderHistoryRelations {
    /**
     * This model relationship belongs to \App\Models\User.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * This model relationship belongs to \App\Models\Product\Product.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function product()
    {
        return $this->belongs('App\Models\Product\Product');
    }

    /**
     * This model relationship belongs to \App\Models\User\UserPaymentConfig.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function userPaymentConfig()
    {
        return $this->belongsTo('App\Models\User\UserPaymentConfig', 'user_payment_config_id');
    }

    /**
     * This model relationship has many \App\Models\Order\OrderHistoryProducts.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function orderHistoryProducts()
    {
        return $this->hasMany('App\Models\Order\OrderHistoryProducts', 'order_history_id');
    }

    /**
     * This model relationship belongs to \App\Models\User\UsersAddress.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function usersAddresses()
    {
        return $this->belongsTo('App\Models\User\UsersAddress', 'users_addresses_id');
    }
}
