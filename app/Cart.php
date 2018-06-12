<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Auth;

class Cart extends Model
{
    protected $table = 'cart';

    public static function count()
    {
        if(Auth::check())
        {
            // return static::selectRaw('')
            //     ->where('year','month')
            //     ->all()->count();
        }
        else
        {
            $cacheCartCount = Cache::get('cc');
// dd($cacheCartCount);
            return count($cacheCartCount) ?? 0;
        }
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
