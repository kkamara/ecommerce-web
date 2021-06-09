<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderHistoryProducts extends Model
{
    use HasFactory;

    /**
     * This models immutable values.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * This model instance belongs to \App\Models\OrderHistory.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function orderHistory()
    {
        return $this->belongsTo('App\Models\OrderHistory', 'order_history_id');
    }

    /**
     * This model instance belongs to \App\Models\Product.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    /**
     * Return a formatted cost attribute.
     *
     * @return  string
     */
    public function getFormattedCostAttribute()
    {
        return "£".number_format($this->attributes['cost'], 2);
    }
}
