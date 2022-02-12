<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class OrderHistoryProducts extends Model
{
    use HasFactory;

    /**
     * This models immutable values.
     *
     * @property Array
     */
    protected $guarded = [];

    /**
     * This model instance belongs to \App\Models\Order\OrderHistory.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function orderHistory()
    {
        return $this->belongsTo('App\Models\Order\OrderHistory', 'order_history_id');
    }

    /**
     * This model instance belongs to \App\Models\Product\Product.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product\Product', 'product_id');
    }

    /**
     * Return a formatted cost attribute.
     *
     * @return  String
     */
    public function formattedCost(): Attribute
    {
        return new Attribute(
            fn ($value, $attributes) => "£".number_format($attributes['cost'], 2)
        );
    }
}
