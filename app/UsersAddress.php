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

    /**
     * Shows each address value in a single line when this instance is treated as a string.
     *
     * @return string
     */
    public function __tostring()
    {
        return $this->attributes['building_name'].' '.$this->attributes['street_address1'].
               ' '.$this->attributes['street_address2'].' '.$this->attributes['street_address3'].
               ' '.$this->attributes['street_address4'].' '.$this->attributes['county'].
               ' '.$this->attributes['city'].' '.$this->attributes['postcode'].
               ' '.$this->attributes['country'].' '.($this->attributes['formatted_phone_number'] ?? null).
               ' '.($this->attributes['formatted_mobile_number'] ?? null);
    }
}
