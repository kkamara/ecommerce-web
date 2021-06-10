<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FlaggedProductReview extends Model
{
    use HasFactory;

    /**
     * This models immutable values.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * This model relationship belongs to \App\Models\Product\ProductReview
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function productReview()
    {
        return $this->belongsTo('App\Models\Product\ProductReview', 'product_reviews_id');
    }

    /**
     * Find whether a particular IP Address has flagged a product review.
     *
     * @param   string $ipAddress
     * @param   \App\Models\Product\ProductReview $id
     * @return  Illuminate\Support\Collection
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
        /** Query for unanswered flagged reviews. */
        $ids = DB::Select('SELECT product_reviews_id
        FROM
        (
            SELECT product_reviews_id
            FROM flagged_product_reviews
            GROUP BY product_reviews_id
            HAVING COUNT(*) > 4
        ) T1');

        /** Push results to a standard array. */
        $idsArray = array();
        foreach($ids as $id)
        {
            $idsArray[] = $id->product_reviews_id;
        }

        return $query->whereIn('product_reviews_id', $idsArray)->groupBy('product_reviews_id');
    }

    /**
     * Gets the number of times an \App\Models\Product\ProductReview has been flagged.
     *
     * @param  \App\Models\Product\ProductReview $id
     * @return int
     */
    public static function getFlagCount($id)
    {
        return (int) self::where([
            'product_reviews_id' => $id,
        ])->count();
    }

    /**
     * Returns an error in the decision process when a moderator reviews a flagged \App\Models\Product\ProductReview.
     *
     * @param  int  $userId, string  $companyName, int  $usersAddressId
     * @return string|false The error text or false implying no errors occurred.
     */
    public static function getModDecisionError($reasonGiven, $acceptDecision, $declineDecision)
    {
        if(! isset($reasonGiven))
            return 'Reason not provided.';
        elseif(strlen($reasonGiven) < 10)
            return 'Reason must be longer than 10 characters.';
        elseif(strlen($reasonGiven) > 191)
            return 'Reason exceeds maximum length 191.';

        if(! isset($acceptDecision) && ! isset($declineDecision))
            return 'Error processing that request. Contact system administrator.';

        return FALSE;
    }
}
