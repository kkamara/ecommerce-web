<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPaymentAddress extends Model
{
    protected $guarded = [];

    public function userPaymentConfig()
    {
        return $this->belongsTo('App\UserPaymentConfig', 'user_payment_config_id');
    }
}
