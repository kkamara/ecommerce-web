<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $guarded = ['name'];

    protected $dates = ['deleted_at'];

    public function getPathAttribute()
    {
        return url('/companies/'.$this->attributes['slug']);
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'company_id');
    }
}
