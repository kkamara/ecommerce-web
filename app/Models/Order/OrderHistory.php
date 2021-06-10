<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order\OrderHistoryProducts;

class OrderHistory extends Model
{
    use HasFactory;

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
     * This model relationship belongs to \App\Models\User.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * This model relationship belongs to \App\Models\Product.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function product()
    {
        return $this->belongs('App\Models\Product');
    }

    /**
     * This model relationship belongs to \App\Models\User\UserPaymentConfig.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function userPaymentConfig()
    {
        return $this->belongsTo('App\Models\User\UserPaymentConfig', 'user_payment_config_id');
    }

    /**
     * This model relationship has many \App\Models\Order\OrderHistoryProducts.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function orderHistoryProducts()
    {
        return $this->hasMany('App\Models\Order\OrderHistoryProducts', 'order_history_id');
    }

    /**
     * This model relationship belongs to \App\Models\User\UsersAddress.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function usersAddresses()
    {
        return $this->belongsTo('App\Models\User\UsersAddress', 'users_addresses_id');
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
