<?php

namespace App\Models\Cart\Traits;

trait CartRelations {
    /**
     * This model relationship belongs to \App\Models\User.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * This model relationship belongs to \App\Models\Product\Product.
     *
     * @return  \Illuminate\Database\Eloquent\Model
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product\Product');
    }
}
