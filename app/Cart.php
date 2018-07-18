<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Auth;

class Cart extends Model
{
    protected $table = 'cart';
    protected $guarded = [];

    public $timestamps = false;

    public static function count()
    {
        $count = 0;

        if(Auth::check())
        {
            $user = auth()->user();
            $cacheCart = $user->getDbCart();

            foreach($cacheCart as $cc)
            {
                $count += $cc['amount'];
            }
        }
        else
        {
            $cacheCart = Cache::get('cc');

            if($cacheCart !== NULL)
            {
                foreach($cacheCart as $cc)
                {
                    $count += $cc['amount'];
                }
            }
        }

        return $count;
    }

    public static function price()
    {
        $price = 0;

        if(Auth::check())
        {
            $user = auth()->user();
            $cacheCart = $user->getDbCart();

            foreach($cacheCart as $cc)
            {
                $price += $cc['product']->cost * $cc['amount'];
            }
        }
        else
        {
            $cacheCart = getCacheCart();

            if($cacheCart !== NULL)
            {
                foreach($cacheCart as $cc)
                {
                    $price += $cc['product']->cost * $cc['amount'];
                }
            }
        }

        return "£".number_format($price, 2);
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function getDbCart()
    {
        $products = \App\Cart::where('user_id', $this->attributes['id'])->get();

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
