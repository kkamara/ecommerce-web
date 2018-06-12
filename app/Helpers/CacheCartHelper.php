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