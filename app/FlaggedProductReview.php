<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\FlaggedProductReview;
use App\ProductReview;

class FlaggedProductReview extends Model
{
    protected $guarded = [];

    public static function hasIpFlaggedThisReview($ip)
    {
        return self::where('flagged_from_ip', $ip)->get();
    }

    // SELECT product_reviews_id
    // FROM
    // (
    //     SELECT id, product_reviews_id
    //     FROM flagged_product_reviews
    //     GROUP BY product_reviews_id
    //     HAVING COUNT(*) > 4
    // ) T1

    public static function scopeWhereUnanswered($query)
    {
        // return $query->where('SUM(product_reviews_id)', '>', '4');
        return $query->where(DB::Raw('COUNT(product_reviews_id)'));
    }
}
