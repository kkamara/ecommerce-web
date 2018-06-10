
<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

    public function moveCacheCartToDbCart()
    {

    }
}
