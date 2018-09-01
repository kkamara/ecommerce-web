<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\FlaggedProductReview;
use App\ProductReview;

class FlaggedProductReview extends Model
{
    protected $guarded = [];

    /**
     * This model relationship belongs to \App\ProductReview
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function productReview()
    {
        return $this->belongsTo('App\ProductReview', 'product_reviews_id');
    }

    /**
     * Find whether a particular IP Address has flagged a product review.
     *
     * @param string $ipAddress, \App\ProductReview $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function hasIpFlaggedThisReview($ipAddress, $id)
    {
        return self::where([
            'flagged_from_ip' => $ipAddress,
            'product_reviews_id' => $id,
        ])->get();
    }

    /**
     * Gets flagged reviews that haven't been responded to yet.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public static function scopeWhereUnanswered($query)
    {
        /**
         * Query for unanswered flagged reviews.
         */
        $ids = DB::Select('SELECT product_reviews_id
        FROM
        (
            SELECT product_reviews_id
            FROM flagged_product_reviews
            GROUP BY product_reviews_id
            HAVING COUNT(*) > 4
        ) T1');

        /**
         * Push results to a standard array.
         */
        $idsArray = array();
        foreach($ids as $id)
        {
            $idsArray[] = $id->product_reviews_id;
        }

        return $query->whereIn('product_reviews_id', $idsArray)->groupBy('product_reviews_id');
    }

    /**
     * Gets the number of times an \App\ProductReview has been flagged.
     *
     * @param \App\ProductReview $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function getFlagCount($id)
    {
        return self::where([
            'product_reviews_id' => $id,
        ])->count();
    }
}
