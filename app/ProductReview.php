<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FlaggedProductReview;
use App\ProductReview;

class ProductReview extends Model
{
    protected $guarded = [];

    /**
     * This model relationship has \App\FlaggedProductReview
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function flaggedProductReview()
    {
        return $this->hasMany('App\FlaggedProductReview', 'product_reviews_id');
    }

    /**
     * This model relationship belongs to \App\User
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * This model relationship has one to \App\Product
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function product()
    {
        return $this->hasOne('App\Product');
    }

    public function isFlaggedFiveTimes()
    {
        return FlaggedProductReview::getFlagCount() > 4;
    }
}
