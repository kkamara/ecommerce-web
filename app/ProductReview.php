<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FlaggedProductReview;
use App\ProductReview;

class ProductReview extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function product()
    {
        return $this->hasOne('App\Product');
    }

    public function getFlagCount()
    {
        return FlaggedProductReview::where([
            'product_reviews_id' => $this->attributes['id'],
        ])->count();
    }

    public function isFlaggedFiveTimes()
    {
        return $this->getFlagCount() > 4;
    }
}
