<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order\OrderHistoryProducts;
use App\Models\Order\Traits\OrderHistoryRelations;

class OrderHistory extends Model
{
    use HasFactory;
    use OrderHistoryRelations;

    /**
     * This models table name is 'order_history' instead of 
     * 'order_histories' so must be set explicitly here.
     *
     * @property String
     */
    protected $table = 'order_history';

    /**
     * This models immutable values.
     *
     * @property Array
     */
    protected $guarded = [];

    /**
     * Generates a new reference number.
     *
     * @return String
     */
    public function generateRefNum()
    {
        $param = str_shuffle("00000111112222233333444445555566666777778888899999");
        return substr($param, 0, 8);
    }

    /**
     * Gets total price of items in a single order.
     *
     * @return  Int
     */
    public function amountTotal(): Attribute
    {
        return new Attribute(
            fn ($value, $attributes) => (int) OrderHistoryProducts::where(['order_history_id' => $attributes['id']])->sum('amount'),
        );
    }

    /**
     * Return the formatted cost attribute.
     *
     * @return  String
     */
    public function formattedCost(): Attribute
    {
        $cost = 0;
        
        foreach($this->orderHistoryProducts as $orderHistoryProducts) {
            $cost += $orderHistoryProducts->cost;
        }

        return new Attribute(
            fn () => sprintf("£%s", number_format(((float) $cost) / 100, 2)),
        );
    }
}
