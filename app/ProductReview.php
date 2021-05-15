<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FlaggedProductReview;
use App\ProductReview;

class ProductReview extends Model
{
    /** 
     * This models immutable values.
     *
     * @var array 
     */
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

    /**
     * Finds whether an \App\ProductReview has been flagged 5 times.
     *
     * @return bool
     */
    public function isFlaggedFiveTimes()
    {
        return FlaggedProductReview::getFlagCount($this->attributes['id']) > 4;
    }

    /**
     * Gets a shortened content attribute from current model instance.
     *
     * @return string
     */
    public function getShortContentAttribute()
    {
        $content = $this->attributes['content'];
        $limit = mt_rand(150, 200);

        return strlen($content) > $limit ? substr($content, 0, $limit) . '...' : $content;
    }
}
