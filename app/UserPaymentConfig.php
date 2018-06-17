<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPaymentConfig extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function orderHistory()
    {
        return $this->hasOne('App\OrderHistory');
    }

    public function userPaymentAddress()
    {
        return $this->hasMany('App\UserPaymentAddress', 'user_payment_config_id');
    }
}
