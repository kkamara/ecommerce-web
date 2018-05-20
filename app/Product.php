<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function orderHistory()
    {
        return $this->hasMany('App\OrderHistory', 'product_id');
    }

    public function productReviews()
    {
        return $this->hasMany('App\ProductReviews', 'product_id');
    }

    public function getFormattedCostAttribute()
    {
        return "£".number_format($this->attributes['cost'], 2);
    }

    public function getCostAttribute()
    {
        return $this->attributes['cost'];
    }
}
