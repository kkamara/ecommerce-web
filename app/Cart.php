<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use App\Helpers\SessionCart;
use Auth;

class Cart extends Model
{
    use HasFactory;

    /**
     * This models table name is 'cart' instead of 'carts' so must be set explicitly here.
     *
     * @var string
     */
    protected $table = 'cart';

    /**
     * This models immutable values are stored in this array.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Disable created_at and updated_at columns for this model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Gets the number of items in the user's session or db cart,
     * depending on whether the user is authenticated.
     *
     * @return  int
     */
    public static function count()
    {
        $count = 0;

        if(Auth::check())
        {
            $user = auth()->user();
            $sessionCart = $user->getDbCart();
        }
        else
        {
            $sessionCart = Session::get('cc');
        }

        if(empty($sessionCart)) return 0;

        foreach($sessionCart as $cc)
        {
            $count += $cc['amount'];
        }

        return $count;
    }

    /**
     * Gets the price of the total amount of items in the session or db cart,
     * depending on whether the user is authenticated.
     *
     * @return  string
     */
    public static function price()
    {
        $price = 0;

        if(Auth::check())
        {
            $user = auth()->user();
            $sessionCart = $user->getDbCart();
        }
        else
        {
            $sessionCart = SessionCart::getSessionCart();
        }

        if(empty($sessionCart)) return '£0.00';

        foreach($sessionCart as $cc)
        {
            $price += $cc['product']->cost * $cc['amount'];
        }

        return "£".number_format($price, 2);
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
     * This model relationship belongs to \App\Product.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    /**
     * Gets the products assigned to the authenticated user.
     *
     * @return array|int
     */
    public function getDbCart()
    {
        $products = self::where('user_id', $this->user->id)->get();

        if(! $products->isEmpty())
        {
            $cart = array();

            foreach($products as $product)
            {
                $id = 'item-'.$product->product_id;

                if(! isset($cart[$id]))
                {
                    $cart[$id] = array(
                        'product' => $product->product,
                        'amount' => 1,
                    );
                }
                else
                {
                    $cart[$id]['amount'] += 1;
                }
            }

            return $cart;
        }
        else
        {
            return 0;
        }
    }
}
