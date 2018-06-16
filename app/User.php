<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at'];

    public function getPathAttribute()
    {
        return url('/users/'.$this->attributes['slug']);
    }

    public function getNameAttribute()
    {
        return $this->attributes['first_name'] .' ' . $this->attributes['last_name'];
    }

    public function cart()
    {
        return $this->hasMany('App\Cart', 'user_id');
    }

    public function company()
    {
        return $this->hasOne('App\Company', 'user_id');
    }

    public function orderHistory()
    {
        return $this->hasMany('App\OrderHistory', 'user_id');
    }

    public function productReviews()
    {
        return $this->hasMany('App\ProductReview', 'user_id');
    }

    public function userPaymentConfig()
    {
        return $this->hasMany('App\UserPaymentConfig', 'user_id');
    }

    public function addProductToDbCart()
    {

    }

    public function moveCacheCartToDbCart($cacheCart)
    {
        foreach($cacheCart as $cc)
        {
            for($i=0; $i<$cc['amount']; $i++)
            {
                \App\Cart::insert([
                    'user_id' => $this->attributes['id'],
                    'product_id' => $cc['product']->id,
                ]);
            }
        }

        Cache::forget('cc');
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
