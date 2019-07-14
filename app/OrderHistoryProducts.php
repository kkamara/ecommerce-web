<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderHistoryProducts extends Model
{
    /** 
     * This models immutable values.
     *
     * @var array 
     */
    protected $guarded = [];

    /**
     * Attributes to automatically append onto the response.
     * 
     * @var array
     */
    protected $appends = [
        'formatted_cost'
    ];

    /**
     * This model instance belongs to \App\OrderHistory.
     * 
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function orderHistory()
    {
        return $this->belongsTo('App\OrderHistory', 'order_history_id');
    }

    /**
     * This model instance belongs to \App\Product.
     * 
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
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
