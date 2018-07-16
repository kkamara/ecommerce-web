<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderHistoryProducts extends Model
{
    protected $guarded = [];

    public function orderHistory()
    {
        return $this->belongsTo('App\OrderHistory', 'order_history_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }
}
