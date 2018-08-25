<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FlaggedProductReview;
use App\ProductReview;

class FlaggedProductReview extends Model
{
    public static function hasIpFlaggedThisReview($ip)
    {
        return self::where('flagged_from_ip', $ip)->get();
    }
}
