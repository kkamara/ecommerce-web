<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class UserPaymentConfig extends Model
{
    use HasFactory;

    /**
     * This models immutable values.
     *
     * @property Array
     */
    protected $guarded = [];

    /**
     * This model relationship belongs to \App\Models\User.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * This model relationship belongs to \App\Models\Order\OrderHistory.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function orderHistory()
    {
        return $this->hasOne('App\Models\Order\OrderHistory');
    }

    /**
     * Set a publicily accessible identifier to get the hidden cart number for this unique instance.
     *
     * @return  String
     */
    public function hiddenCardNumber(): Attribute
    {
        $cardNumber = $this->attributes['card_number'];

        $cardNumber = substr($cardNumber, -4, strlen($cardNumber));
        $cardNumber = str_pad($cardNumber, 16, "*", STR_PAD_LEFT);

        return new Attribute(fn () => $cardNumber);
    }

    /**
     * Set a publicily accessible identifier to get the expiry date for this unique instance.
     *
     * @return  \Carbon\Carbon
     */
    public function expiryDate(): Attribute
    {
        return new Attribute(
            fn ($value, $attributes) => Carbon::createFromDate(
                $attributes['expiry_year'], 
                $attributes['expiry_month'], 
                1,
            )->format('m/Y'),
        );
    }

    /**
     * Set a publicily accessible identifier to get the edit expiry date for this unique instance.
     *
     * @return  \Carbon\Carbon
     */
    public function editExpiryDate(): Attribute
    {
        return new Attribute(
            fn ($value, $attributes) => Carbon::createFromDate(
                $attributes['expiry_year'], 
                $attributes['expiry_month'], 
                1,
            )->format('Y-m'),
        );
    }

    /**
     * Set a publicily accessible identifier to get the edit 
     * expiry date for this unique instance.
     *
     * @return  \Carbon\Carbon
     */
    public function formattedPhoneNumber(): Attribute
    {
        return new Attribute(
            fn ($value, $attributes) => sprintf(
                "%s %s",
                $attributes['phone_number_extension'],
                $attributes['phone_number'],
            )
        );
    }

    /**
     * Set a publicily accessible identifier to get the 
     * formatted mobile number for this unique instance.
     *
     * @return  String
     */
    public function formattedMobileNumber(): Attribute
    {
        return new Attribute(
            fn ($value, $attributes) => sprintf(
                "%s %s",
                $attributes['mobile_number_extension'],
                $attributes['mobile_number'],
            )
        );
    }
}
