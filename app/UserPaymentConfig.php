<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPaymentConfig extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function orderHistory()
    {
        return $this->hasOne('App\OrderHistory');
    }
}
