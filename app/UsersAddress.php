<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class UsersAddress extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function orderHistory()
    {
        return $this->hasMany('App\OrderHistory', 'users_addresses_id');
    }

    public function getFormattedPhoneNumberAttribute()
    {
        return $this->attributes['phone_number_extension'] . ' ' . $this->attributes['phone_number'];
    }

    public function getFormattedMobileNumberAttribute()
    {
        return $this->attributes['mobile_number_extension'] . ' ' . $this->attributes['mobile_number'];
    }
}
