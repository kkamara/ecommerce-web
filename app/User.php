<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Helpers\SessionCart;

use Validator;

class User extends Authenticatable
{
    /** This model uses the Notifiable trait for notifications. */
    use Notifiable;

    /** This model uses the SoftDeletes trait for a deleted_at datetime column. */
    use SoftDeletes;
    
    /** This model uses the HasRoles trait for a user being able to have a role. */
    use HasRoles;

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

    /** 
     * This models immutable date values.
     * 
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Set a publicily accessible identifier to get the path for this unique instance.
     * 
     * @return  string
     */
    public function getPathAttribute()
    {
        return url('/users/'.$this->attributes['slug']);
    }

    /**
     * Set a publicily accessible identifier to get the name attribute for this unique instance.
     * 
     * @return  string
     */
    public function getNameAttribute()
    {
        return $this->attributes['first_name'] .' ' . $this->attributes['last_name'];
    }

    /**
     * This model relationship has many \App\Cart.
     * 
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function cart()
    {
        return $this->hasMany('App\Cart', 'user_id');
    }

    /**
     * This model relationship has one \App\Company.
     * 
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function company()
    {
        return $this->hasOne('App\Company', 'user_id');
    }

    /**
     * This model relationship has many \App\OrderHistory.
     * 
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function orderHistory()
    {
        return $this->hasMany('App\OrderHistory', 'user_id');
    }

    /**
     * This model relationship has many \App\Product.
     * 
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function product()
    {
        return $this->hasMany('App\Product', 'user_id');
    }

    /**
     * Get errors in request data.
     * 
     * @param  array  $data
     * @return array
     */
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

    /**
     * This model relationship has many \App\ProductReview.
     * 
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function productReview()
    {
        return $this->hasMany('App\ProductReview', 'user_id');
    }

    /**
     * This model relationship has many \App\UserPaymentConfig.
     * 
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function userPaymentConfig()
    {
        return $this->hasMany('App\UserPaymentConfig', 'user_id');
    }

    /**
     * This model relationship has many \App\UsersAddress.
     * 
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function userAddress()
    {
        return $this->hasMany('App\UsersAddress', 'user_id');
    }

    /**
     * This model relationship has many \App\UsersAddress.
     * 
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function vendorApplication()
    {
        return $this->hasOne('App\VendorApplication');
    }

    /**
     * Adds a given product to db cart for this user.
     * 
     * @param  \App\Product  $product
     */
    public function addProductToDbCart($product)
    {
        \App\Cart::create([
            'user_id' => $this->attributes['id'],
            'product_id' => $product->id,
        ]);
    }

    /**
     * Moves cache cart to db cart for this user on login.
     * 
     * @param  array  $sessionCart
     */
    public function moveSessionCartToDbCart($sessionCart)
    {
        $userId = $this->attributes['id'];
        
        foreach($sessionCart as $cc)
        {
            if($cc['product']->user_id !== $userId)
            {
                for($i=0; $i<$cc['amount']; $i++)
                {
                    \App\Cart::insert([
                        'user_id' => $userId,
                        'product_id' => $cc['product']->id,
                    ]);
                }
            }
        }

        SessionCart::clearSessionCart();
    }

    /**
     * Gets the database cart for this user.
     * 
     * @return array|int
     */
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

    /**
     * Updates the respective number of products in the user's database cart.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function updateDbCartAmount($request)
    {
        /** Get existing cache cart */
        $sessionCart = $this->getDbCart();

        foreach($sessionCart as $cc)
        {
            /** Check if an amount value for this product was given in the request */
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
                    /** Push to $sessionCart the product with new amount value */
                    \App\Cart::insert([
                        'user_id' => $this->attributes['id'],
                        'product_id' => $product_id,
                    ]);
                }
            }
        }
    }

    /**
     * Deletes all items from user's database cart.
     */
    public function deleteDbCart()
    {
        \App\Cart::where('user_id', $this->attributes['id'])->delete();
    }

    /**
     * Checks whether a given slug already exists for an instance of this model.
     * 
     * @param  string  $slug
     * @return bool
     */
    public static function slugIsUnique($slug)
    {
        $slugs = User::where('slug', $slug)->get();

        return !empty($slugs) ? TRUE : FALSE;
    }

    /**
     * Checks a given slug is unique and creates a new unique slug if necessary.
     * 
     * @param  string  $slug
     * @return string
     */
    public static function generateUniqueSlug($slug)
    {
        $param = str_shuffle("00000111112222233333444445555566666777778888899999");

        while(! Self::slugIsUnique($slug) )
        {
            $slug .= substr($param, 0, mt_rand(4, 8));
        }

        return $slug;
    }
    
    /**
     * Check if an instance of this model has a role.
     * 
     * @return bool
     */
    public function hasNoRole()
    {
        return !$this->hasRole('vendor') && !$this->hasRole('moderator');
    }

    public static function getRegisterErrors(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $basicErrors = $validator->errors();

        $validator = Validator::make($request->all(), [
            'building_name' => 'required|string|max:191',
            'street_address1' => 'required|max:191',
            'street_address2' => 'max:191',
            'street_address3' => 'max:191',
            'street_address4' => 'max:191',
            'postcode' => 'required|string|min: 5|max:191',
            'city' => 'required|string|min: 4|max:191',
            'country' => 'required|string|min: 4|max:191',
            'phone_number_ext' => 'required|min: 2|max:191',
            'phone_number' => 'required|min: 5|max:191',
            'mobile_number_ext' => 'max:191',
            'mobile_number' => 'max:191',
        ]);
        $addressErrors = $validator->errors();

        $validator = Validator::make($request->all(), [
            'card_holder_name' => 'required|min: 6|max: 191',
            'card_number' => 'required|digits: 16',
            'expiry_date' => 'required', // format 2018-01
        ]);
        $billingErrors = $validator->errors();

        if(false == $basicErrors->isEmpty() || false == $addressErrors->isEmpty() || false == $billingErrors->isEmpty()) {
            $present = true;
        } else {
            $present = false;
        }

        return array(
            'basic' => false == $basicErrors->isEmpty() ? $basicErrors : array(),
            'address' => false == $addressErrors->isEmpty() ? $addressErrors : array(),
            'billing' => false == $billingErrors->isEmpty() ? $billingErrors : array(),
            'present' => $present,
        ); 
    }
}
