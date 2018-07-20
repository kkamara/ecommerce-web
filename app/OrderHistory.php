<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $table = 'order_history';
    protected $guarded = [];

    public static function generateRefNum()
    {
        $param = str_shuffle("00000111112222233333444445555566666777778888899999");
        return substr($param, 0, 8);
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function product()
    {
        return $this->belongs('App\Product');
    }

    public function userPaymentConfig()
    {
        return $this->belongsTo('App\UserPaymentConfig', 'user_payment_config_id');
    }

    public function orderHistoryProducts()
    {
        return $this->hasOne('App\OrderHistoryProducts', 'order_history_id');
    }
}
