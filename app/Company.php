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

    /**
     * Find whether a given company name already exists in this model.
     *
     * @param  string  $companyName
     */
    public static function doesCompanyNameExist($companyName)
    {
        return ! self::where('name', '=', $companyName)->get()->isEmpty();
    }

    public function belongsToUser($userId)
    {
        return $userId === $this->user_id;
    }
}
