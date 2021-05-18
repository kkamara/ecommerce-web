<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * This models immutable values.
     *
     * @var array
     */
    protected $guarded = ['name'];

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
        return url('/companies/'.$this->attributes['slug']);
    }

    /**
     * This model relationship belongs to \App\Models\User.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    /**
     * This model relationship has many \App\Product.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
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

    /**
     * Check if this model relationship belongs to a given user id.
     *
     * @param   int  $userId
     * @return  bool
     */
    public function belongsToUser($userId)
    {
        return $userId === $this->user_id;
    }
}
