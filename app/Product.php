<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Validator;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $dates = ['deleted_at'];

    public function getPathAttribute()
    {
        return url('/products/'.$this->attributes['id']);
    }

    public function getImagePathAttribute()
    {
        return $this->attributes['image_path'] ?? '/image/products/default/not-found.jpg';
    }

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }

    public function productReview()
    {
        return $this->hasMany('App\ProductReview', 'product_id');
    }

    public function orderHistoryProducts()
    {
        return $this->hasMany('App\OrderHistoryProducts', 'product_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function getFormattedCostAttribute()
    {
        return "£".number_format($this->attributes['cost'], 2);
    }

    public function getCostAttribute()
    {
        return $this->attributes['cost'];
    }

    public function scopeGetProducts($query, $request)
    {
        $querySearch   = $request->input('query');
        $min     = $request->input('min_price');
        $max     = $request->input('max_price');
        $sort_by = $request->input('sort_by');

        $query->select('products.id', 'products.name', 'products.user_id', 'products.company_id', 'products.short_description', 'products.long_description', 'products.product_details', 'products.image_path', 'products.cost', 'products.shippable', 'products.free_delivery', 'products.created_at', 'products.updated_at');
        $whereClause = array();

        if(isset($querySearch))
        {
            $querySearch = filter_var($querySearch, FILTER_SANITIZE_STRING);
            array_push($whereClause, [
                'products.name', 'LIKE', "$querySearch%"
            ]);
        }
        if(isset($min))
        {
            $min = filter_var($min, FILTER_SANITIZE_NUMBER_FLOAT);
            array_push($whereClause, [
                'products.cost', '>', $min
            ]);
        }
        if(isset($max))
        {
            $max = filter_var($max, FILTER_SANITIZE_NUMBER_FLOAT);
            array_push($whereClause, [
                'products.cost', '<', $max
            ]);
        }

        if(isset($whereClause)) $query->where($whereClause);

        switch($sort_by)
        {
            case 'pop': // most popular
                $query->leftJoin('order_history_products', 'products.id', '=', 'order_history_products.product_id')
                    ->groupBy('order_history_products.product_id');
            break;
            case 'top': // top rated
                $query->leftJoin('product_reviews', 'products.id', '=', 'product_reviews.product_id')
                    ->withCount(['productReview as review' => function($query) {
                        $query->select(DB::raw('avg(product_reviews.score) as average_rating'));
                    }])->groupBy('product_reviews.product_id')->orderByDesc('review');
            break;
            case 'low': // lowest price
                $query->orderBy('cost', 'ASC');
            break;
            case 'hig': // highest price
                $query->orderBy('cost', 'DESC');
            break;
            default:
            break;
        }

        $query->orderBy('products.id', 'DESC')->distinct();

        return $query;
    }

    public function didUserPurchaseProduct($user_id)
    {
        foreach($this->orderHistoryProducts()->get() as $product){
            $orderHistory = $product->orderHistory()->get();

            foreach($orderHistory as $order)
            {
                if($order->user_id == $user_id)
                {
                    return TRUE;
                }
            }
        }

        return FALSE;
    }

    public function didUserReviewProduct($user_id)
    {
        foreach($this->productReview()->get() as $review){

            if($review->user_id == $user_id)
            {
                return TRUE;
            }
        }

        return FALSE;
    }

    public function getReviewAttribute()
    {
        $review = \App\ProductReview::select(DB::raw('avg(score) as review'))
            ->where('product_id', $this->attributes['id'])
            ->groupBy('product_id')
            ->distinct()->first();

        return isset($review->review) ? number_format((float)$review->review, 2, '.', '') : '0.00';
    }

    /**
     * Get products that belong to a given vendor.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $query, int  $companyId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scopeGetCompanyProducts($query, $companyId)
    {
        return $query->where('company_id', '=', $companyId);
    }

    /**
     * Returns boolean indicating if authenticated user owns the current instance of this model.
     *
     * @return bool
     */
    public function doesUserOwnProduct()
    {
        return $this->company->user_id === auth()->user()->id;
    }

    /**
     * Returns boolean indicating whether this model instance is using a default image.
     *
     * @return bool
     */
    public function usingDefaultImage()
    {
        return $this->attributes['image_path'] === NULL;
    }

    /**
     * Returns an array of errors for \App\Http\Controllers\CompanyProductController requests.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $request
     * @return array
     */
    public static function getErrors($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'cost' => 'required|regex:/\d.\d/',
            'shippable' => 'required|boolean|between:0,1',
            'free_delivery' => 'required|boolean|between:0,1',
            'use_default_image' => 'required|boolean|between:0,1',
            'image' => 'image',
            'short_description' => 'required|max:191',
            'long_description' => 'required',
            'product_details' => 'required',
        ]);
        $errors = $validator->errors()->all();

        /** If user doesnt want to use a default image but has not uploaded an image */
        if((bool) $request->input('use_default_image') === FALSE && Input::hasFile('image') === FALSE)
            $errors[] = 'You have opted to not use a default image but you have no provided one.';

        /** If user wants to use default image but has uploaded an image anyway */
        if((bool) $request->input('use_default_image') === TRUE && Input::hasFile('image') === TRUE)
            $errors[] = 'You have opted to use a default image but you provided one anyway.';

        return $errors;
    }
}
