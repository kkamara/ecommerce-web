<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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

    public function product()
    {
        return $this->hasMany('App\Product', 'user_id');
    }

    public function getOrderHistoryErrors($data)
    {
        $errors = array();

        if(empty($data['delivery']))
        {
            array_push($errors, 'No delivery address chosen');
        }
        elseif(sizeof($data['delivery']) > 1)
        {
            array_push($errors, 'Please select just one delivery address');
        }

        if(empty($data['billing']))
        {
            array_push($errors, 'No billing card chosen');
        }
        elseif(sizeof($data['billing']) > 1)
        {
            array_push($errors, 'Please select just one billing card');
        }

        return $errors;
    }

    public function productReview()
    {
        return $this->hasMany('App\ProductReview', 'user_id');
    }

    public function userPaymentConfig()
    {
        return $this->hasMany('App\UserPaymentConfig', 'user_id');
    }

    public function userAddress()
    {
        return $this->hasMany('App\UsersAddress', 'user_id');
    }

    public function vendorApplication()
    {
        return $this->hasOne('App\VendorApplication');
    }

    public function addProductToDbCart($product)
    {
        \App\Cart::create([
            'user_id' => $this->attributes['id'],
            'product_id' => $product->id,
        ]);
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

    public function updateDbCartAmount($request)
    {
        $cacheCart = $this->getDbCart();

        foreach($cacheCart as $cc)
        {
            $product_id = $cc['product']->id;
            $amount = $request->get('amount-' . $product_id);

            \App\Cart::where([
                'user_id' => $this->attributes['id'],
                'product_id' => $product_id,
            ])->delete();

            if($amount !== NULL && $amount != 0)
            {
                for($i=0; $i<$amount; $i++)
                {
                    \App\Cart::insert([
                        'user_id' => $this->attributes['id'],
                        'product_id' => $product_id,
                    ]);
                }
            }
        }
    }

    public function deleteDbCart()
    {
        \App\Cart::where('user_id', $this->attributes['id'])->delete();
    }

    public static function slugIsUnique($slug)
    {
        $slugs = User::where('slug', $slug)->get();

        return !empty($slugs) ? TRUE : FALSE;
    }

    public static function generateUniqueSlug($slug)
    {
        $param = str_shuffle("00000111112222233333444445555566666777778888899999");

        while(! Self::slugIsUnique($slug) )
        {
            $slug = $slug . substr($param, 0, mt_rand(4, 8));
        }

        return $slug;
    }
    
    public function hasNoRole()
    {
        return !$this->hasRole('vendor') && !$this->hasRole('moderator');
    }
}
