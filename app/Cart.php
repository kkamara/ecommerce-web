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
            $count = 0;
            $cacheCart = Cache::get('cc');

            if($cacheCart !== NULL)
            {
                foreach($cacheCart as $cc)
                {
                    $count += $cc['amount'];
                }
            }

            return $count;
        }
    }

    public static function price()
    {
        if(Auth::check())
        {
            // return static::selectRaw('')
            //     ->where('year','month')
            //     ->all()->count();
        }
        else
        {
            $price = 0;
            $cacheCart = getCacheCart();

            if($cacheCart !== NULL)
            {
                foreach($cacheCart as $cc)
                {
                    $price += $cc['product']->cost * $cc['amount'];
                }
            }

            return "£".number_format($price, 2);
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
