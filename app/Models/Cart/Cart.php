<?php

namespace App\Models\Cart;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use App\Helpers\SessionCartHelper;
use App\Models\Cart\Traits\CartRelations;

class Cart extends Model
{
    use HasFactory;
    use CartRelations;

    /**
     * This models table name is 'cart' instead of 'carts' so must be set explicitly here.
     *
     * @property String
     */
    protected $table = 'cart';

    /**
     * This models immutable values are stored in this array.
     *
     * @property Array
     */
    protected $guarded = [];

    /**
     * Disable created_at and updated_at columns for this model.
     *
     * @property Bool
     */
    public $timestamps = false;

    /** @property SessionCartHelper */
    protected $sessionCartHelper;
    
    /**
     * @construct
     */
    public function __construct() {
        parent::__construct();
        $this->sessionCartHelper = new SessionCartHelper;
    }

    /**
     * Gets the number of items in the user's session or db cart,
     * depending on whether the user is authenticated.
     *
     * @return  Int
     */
    public function count()
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

        if(empty($sessionCart)) {
            return 0;
        }

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
     * @return  String
     */
    public function price()
    {
        $price = 0;

        if(Auth::check())
        {
            $user = auth()->user();
            $sessionCart = $user->getDbCart();
        }
        else
        {
            $sessionCart = $this->sessionCartHelper->getSessionCart();
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
     * @return Array|Int
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
        
        return 0;
    }
}
