<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Helpers\RedisCartHelper;
use App\Models\User\Traits\UserRelations;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;
    use UserRelations;

    /**
     * The attributes that are mass assignable.
     *
     * @property Array
     */
    protected $fillable = [
        'slug',
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @property Array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @property Array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that shouldn't be overwritten.
     *
     * @property Array
     */
    protected $guarded = [];

    /**
     * @return Array
     */
    public static function getTestUsers() {
        return [
            'mod' => [
                'email' => 'mod@mail.com',
                'password' => 'secret',
            ],
            'vendor' => [
                'email' => 'vendor@mail.com',
                'password' => 'secret',
            ],
            'guest' => [
                'email' => 'guest@mail.com',
                'password' => 'secret',
            ],
        ];
    }

    /**
     * Set a publicily accessible identifier to get the path for this unique instance.
     *
     * @return  Attribute
     */
    public function path(): Attribute
    {
        return new Attribute(fn ($value, $attributes) => url('/users/'.$attributes['slug']));
    }

    /**
     * Set a publicily accessible identifier to get the name 
     * attribute for this unique instance.
     *
     * @return  Attribute
     */
    public function name(): Attribute
    {
        return new Attribute(
            fn ($value, $attributes) => $attributes['first_name'] .' ' . $attributes['last_name'],
        );
    }

    /**
     * Get errors in request data.
     *
     * @param  Array  $data
     * @return Array
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
     * Adds a given product to db cart for this user.
     *
     * @param  \App\Models\Product  $product
     */
    public function addProductToDbCart($product)
    {
        \App\Models\Cart\Cart::create([
            'user_id' => $this->attributes['id'],
            'product_id' => $product->id,
        ]);
    }

    /**
     * Moves cache cart to db cart for this user on login.
     *
     * @param  Array  $sessionCart
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
                    \App\Models\Cart\Cart::insert([
                        'user_id' => $userId,
                        'product_id' => $cc['product']->id,
                    ]);
                }
            }
        }

        (new RedisCartHelper)->clearSessionCart();
    }

    /**
     * Gets the database cart for this user.
     *
     * @return Array|Int
     */
    public function getDbCart()
    {
        // dd($this->attributes);
        $products = \App\Models\Cart\Cart::where('user_id', $this->attributes['id'])->get();

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

    /**
     * Updates the respective number of products in user's db cart.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function updateDbCartAmount($request)
    {
        /** Get existing cache cart */
        $sessionCart = $this->getDbCart();

        foreach($sessionCart as $cc)
        {
            /** Checks if product amount exists in request */
            $product_id = $cc['product']->id;
            $amount = $request->get('amount-' . $product_id);

            \App\Models\Cart\Cart::where([
                'user_id' => $this->attributes['id'],
                'product_id' => $product_id,
            ])->delete();

            if($amount !== NULL && $amount != 0)
            {
                /** Push product to $sessionCart with updated amounts */
                for($i=0; $i<$amount; $i++)
                {
                    \App\Models\Cart\Cart::insert([
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
        \App\Models\Cart\Cart::where('user_id', $this->attributes['id'])->delete();
    }

    /**
     * Checks whether a given slug already exists for an instance of this model.
     *
     * @param  String  $slug
     * @return Bool
     */
    public function slugIsUnique($slug)
    {
        $slugs = User::where('slug', $slug)->get();

        return !empty($slugs) ? TRUE : FALSE;
    }

    /**
     * Checks a given slug is unique and creates a new unique slug if necessary.
     *
     * @param  String  $slug
     * @return String
     */
    public function generateUniqueSlug($slug)
    {
        $param = str_shuffle("00000111112222233333444445555566666777778888899999");

        while(! $this->slugIsUnique($slug) )
        {
            $slug .= substr($param, 0, mt_rand(4, 8));
        }

        return $slug;
    }

    /**
     * Check if an instance of this model has a role.
     *
     * @return Bool
     */
    public function hasNoRole()
    {
        return false === $this->hasRole('vendor') && 
            false === $this->hasRole('moderator');
    }

    /**
     * Gets errors from user registration attempt.
     * 
     * @param \Illuminate\Http\Request $request
     * @return Array
     */
    public function getRegisterErrors(Request $request)
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

        if(
            false == $basicErrors->isEmpty() || 
            false == $addressErrors->isEmpty() || 
            false == $billingErrors->isEmpty()
        ) {
            $present = true;
        } else {
            $present = false;
        }

        return array(
            'basic'   => false == $basicErrors->isEmpty() ? $basicErrors : array(),
            'address' => false == $addressErrors->isEmpty() ? $addressErrors : array(),
            'billing' => false == $billingErrors->isEmpty() ? $billingErrors : array(),
            'present' => $present,
        );
    }
}
