<?php

namespace App\Models\Cart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use App\Helpers\SessionCartHelper;
use App\Models\Cart\Traits\CartRelations;
use Auth;

class Cart extends Model
{
    use HasFactory;
    use CartRelations;

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
            $sessionCart = SessionCartHelper::getSessionCart();
        }

        if(empty($sessionCart)) return '£0.00';

        foreach($sessionCart as $cc)
        {
            $price += $cc['product']->cost * $cc['amount'];
        }

        return "£".number_format($price, 2);
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
