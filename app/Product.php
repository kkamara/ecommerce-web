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
        return $this->attributes['slug'];
    }

    public function getImagePathAttribute()
    {
        return $this->attributes['image_path'] ?? 'image/products/default/not-found.jpg';
    }
}
