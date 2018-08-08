<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorApplication extends Model
{
    protected $guarded = [];

    // check if user has applied with no response
    public static function hasUserApplied($user_id)
    {
        return ! Self::where([
            'user_id' => $user_id,
            'accepted' => null
        ])->get()->isEmpty();
    }

    // check if user has been rejected
    public static function hasApplicationBeenRejected($user_id)
    {
        return ! Self::where([
            'user_id' => $user_id,
            'accepted' => 0
        ])->get()->isEmpty();
    }
}
