<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\OrderHistoryProducts;

class OrderHistory extends Model
{
    
    /** 
     * This models table name is 'order_history' instead of 'order_histories' so must be set explicitly here.
     * 
     * @var string
     */
    protected $table = 'order_history';

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
        'amount_total', 'formatted_cost'
    ];

    /**
     * Generates a new reference number.
     * 
     * @return string
     */
    public static function generateRefNum()
    {
        $param = str_shuffle("00000111112222233333444445555566666777778888899999");
        return substr($param, 0, 8);
    }

    /**
     * This model relationship belongs to \App\User.
     * 
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * This model relationship belongs to \App\Product.
     * 
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function product()
    {
        return $this->belongs('App\Product');
    }

    /**
     * This model relationship belongs to \App\UserPaymentConfig.
     * 
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function userPaymentConfig()
    {
        return $this->belongsTo('App\UserPaymentConfig', 'user_payment_config_id');
    }

    /**
     * This model relationship has many \App\OrderHistoryProducts.
     * 
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function orderHistoryProducts()
    {
        return $this->hasMany('App\OrderHistoryProducts', 'order_history_id');
    }

    /**
     * This model relationship belongs to \App\UsersAddress.
     * 
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function usersAddresses()
    {
        return $this->belongsTo('App\UsersAddress', 'users_addresses_id');
    }

    /**
     * Gets total price of items in a single order.
     * 
     * @return  int
     */
    public function getAmountTotalAttribute()
    {
        return (int) OrderHistoryProducts::where([
            'order_history_id' => $this->attributes['id'],
        ])->sum('amount');
    }

    /**
     * Return the formatted cost attribute.
     * 
     * @return  string
     */
    public function getFormattedCostAttribute()
    {
        return "£".number_format($this->attributes['cost'], 2);
    }
}
