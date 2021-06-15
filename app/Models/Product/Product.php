<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Product\Traits\ProductRelations;
use App\Models\Product\Traits\ProductScopes;
use Illuminate\Http\Request;
use Validator;

class Product extends Model
{
    use SoftDeletes, HasFactory;
    use ProductRelations, ProductScopes;

    /**
     * This models immutable values.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * This models immutable date values.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Set a publicily accessible identifier to get the path for this unique instance.
     *
     * @return  string
     */
    public function getPathAttribute()
    {
        return url('/products/'.$this->attributes['id']);
    }

    /**
     * Set a publicily accessible identifier to get the image path for this unique instance.
     *
     * @return  string
     */
    public function getImagePathAttribute()
    {
        return $this->attributes['image_path'] ?? '/image/products/default/not-found.jpg';
    }

    /**
     * Return the formatted cost attribute.
     *
     * @return  string
     */
    public function getFormattedCostAttribute()
    {
        return "£".number_format($this->attributes['cost'], 2);
    }

    /**
     * Return the cost attribute.
     *
     * @return  string
     */
    public function getCostAttribute()
    {
        return $this->attributes['cost'];
    }

    /**
     * Find whether a given user has purchased this product instance.
     *
     * @param  \App\Models\User  $userId
     * @return bool
     */
    public function didUserPurchaseProduct($userId)
    {
        foreach($this->orderHistoryProducts()->get() as $product){
            $orderHistory = $product->orderHistory()->get();

            foreach($orderHistory as $order)
            {
                if($order->user_id == $userId)
                {
                    return TRUE;
                }
            }
        }

        return FALSE;
    }

    /**
     * Find whether a given user has reviewed this product instance.
     *
     * @param  \App\Models\User  $userId
     * @return bool
     */
    public function didUserReviewProduct($userId)
    {
        foreach($this->productReview()->get() as $review){

            if($review->user_id == $userId)
            {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Return the review attribute.
     *
     * @return  string
     */
    public function getReviewAttribute()
    {
        $review = \App\Models\Product\ProductReview::select(DB::raw('avg(score) as review'))
            ->where('product_id', $this->attributes['id'])
            ->groupBy('product_id')
            ->distinct()->first();

        return isset($review->review) ? number_format((float)$review->review, 2, '.', '') : '0.00';
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
     * Returns boolean indicating whether this model relationship is using a default image.
     *
     * @return bool
     */
    public function usingDefaultImage()
    {
        return $this->attributes['image_path'] === NULL;
    }

    /**
     * Returns an array of errors for \App\Http\Controllers\Models\CompanyProductController\Company requests.
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
        if(
            (bool) $request->input('use_default_image') === FALSE && 
            $request->hasFile('image') === FALSE
        ) {
            $errors[] = 'You have opted to not use a default image but you have not provided one.';
        }

        /** If user wants to use default image but has uploaded an image anyway */
        if(
            (bool) $request->input('use_default_image') === TRUE && 
            $request->hasFile('image') === TRUE
        ) {
            $errors[] = 'You have opted to use a default image but you provided one anyway.';
        }

        return $errors;
    }

    /**
     * Stores image file if exists in request.
     * Will return default product image if request image not detected.
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function uploadImage(Request $request) {
        $result = '/image/products/default/not-found.jpg';

        if(
            null === $request->hasFile('image') ||
            null === $this->company->id
        ) {
            return $result;
        }

        /** @var \Illuminate\Http\UploadedFile|array|null $file */
        $file = $request->file('image');
        $imageName = $file->getClientOriginalName();

        if (
            null === $file ||
            null === $imageName
        ) {
            return $result;
        }

        $storagePath = '/uploads/companies/'.$this->company->id.'/images/';
        $file->move(public_path($storagePath), $imageName);

        $result = $storagePath . $imageName;

        return $result;
    }
}
