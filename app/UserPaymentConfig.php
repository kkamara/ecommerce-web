<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function getHiddenCardNumberAttribute()
    {
        $cardNumber = $this->attributes['card_number'];
        $length = strlen($cardNumber);

        $cardNumber = substr($cardNumber, -4, strlen($cardNumber));
        $cardNumber = str_pad($cardNumber, 16, "*", STR_PAD_LEFT);

        return $cardNumber;
    }

    public function getExpiryDateAttribute()
    {
        return Carbon::createFromDate($this->attributes['expiry_year'], $this->attributes['expiry_month'], 1)->format('m/Y');
    }

    public function getEditExpiryDateAttribute()
    {
        return Carbon::createFromDate($this->attributes['expiry_year'], $this->attributes['expiry_month'], 1)->format('Y-m');
    }
}
