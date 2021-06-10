<?php

namespace App\Models\Company\Traits;

trait CompanyRelations {

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
     * This model relationship has many \App\Models\Product\Product.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function products()
    {
        return $this->hasMany('App\Models\Product\Product', 'company_id');
    }
}
