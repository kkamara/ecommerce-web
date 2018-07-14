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

    public function getCardNumberAttribute()
    {
        $cardNumber = $this->attributes['card_number'];
        $length = strlen($cardNumber);

        $cardNumber = substr($cardNumber, -4, strlen($cardNumber));
        $cardNumber = str_pad($cardNumber, 16, "*", STR_PAD_LEFT);

        return $cardNumber;
    }
}
