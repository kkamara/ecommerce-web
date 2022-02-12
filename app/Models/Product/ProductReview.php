<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Product\FlaggedProductReview;

class ProductReview extends Model
{
    use HasFactory;

    /**
     * This models immutable values.
     *
     * @property Array
     */
    protected $guarded = [];

    /**
     * This model relationship has \App\Models\Product\FlaggedProductReview
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function flaggedProductReview()
    {
        return $this->hasMany('App\Models\Product\FlaggedProductReview', 'product_reviews_id');
    }

    /**
     * This model relationship belongs to \App\Models\User
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * This model relationship has one to \App\Models\Product\Product
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product\Product');
    }

    /**
     * Finds whether an \App\Models\Product\ProductReview has been flagged 5 times.
     *
     * @return Bool
     */
    public function isFlaggedFiveTimes()
    {
        return (new FlaggedProductReview)
            ->getFlagCount($this->attributes['id']) > 4;
    }

    /**
     * Gets a shortened content attribute from current model instance.
     *
     * @return String
     */
    public function shortContent(): Attribute
    {
        $content = $this->attributes['content'];
        $limit = mt_rand(150, 200);

        return new Attribute(
            fn () => strlen($content) > $limit 
                ? substr($content, 0, $limit) . '...' 
                : $content
        );
    }
}
