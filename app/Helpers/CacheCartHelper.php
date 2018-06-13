<?php

function addProductToCacheCart($product)
{
    $expiresAt = now()->addMinutes(120);

    $cacheCart = Cache::get('cc');

    if($cacheCart !== NULL)
    {
        if(is_array($cacheCart))
        {
            $productAlreadyAdded = FALSE;

            foreach($cacheCart as $cc)
            {
                if(is_array($cc) && in_array($product->id, $cc))
                {
                    $productAlreadyAdded = TRUE;
                }
            }

            if($productAlreadyAdded == FALSE)
            {
                $newItem = array(
                    'product' => $product->id,
                    'amount'  => 1,
                );
                array_push($cacheCart, $newItem);
                Cache::put('cc', $cacheCart, $expiresAt);
            }
        }
    }
    else
    {
        $cacheCart = array(
            array(
                'product' => $product->id,
                'amount'  => 1,
            )
        );
        Cache::put('cc', $cacheCart, $expiresAt);
    }
}

function getCacheCart()
{
    $cacheCart = Cache::get('cc');

    $array = array();

    if(isset($cacheCart))
    {
        foreach($cacheCart as $cc)
        {
            $item = App\Cart::where('id', $cc['product'])->first();
            $amount = $cc['amount'];

            array_push($array, array(
                'product' => $item,
                'amount'  => $amount
            ));
        }
    }
    return $array;
}