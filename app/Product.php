<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $dates = ['deleted_at'];

    public function path()
    {
        return url('/products/'.$this->attributes['slug']);
    }

    public function getImagePathAttribute()
    {
        return $this->attributes['image_path'] ?? 'image/products/default/not-found.jpg';
    }

    public function company()
    {
        return $this->belongsTo('App\Company', 'company_id');
    }

    public function getCostAttribute()
    {
        return "£".number_format($this->attributes['cost'], 2);
    }
}
