<?php

function addProductToCacheCart($product)
{
    $expiresAt = now()->addMinutes(120);

    $cacheCart = Cache::get('cc');

    if($cacheCart !== NULL && is_array($cacheCart))
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
// dd($cacheCart);
    $array = array();

    if(isset($cacheCart))
    {
        foreach($cacheCart as $cc)
        {
            $item = App\Product::where('id', $cc['product'])->first();
            $amount = $cc['amount'];

            array_push($array, array(
                'product' => $item,
                'amount'  => $amount
            ));
        }
    }

    return $array;
}

function updateCacheCartAmount($request)
{
    $cacheCart = getCacheCart();
    $array     = array();

    foreach($cacheCart as $cc)
    {
        $product_id = $cc['product']->id;
        $amount = $request->get('amount-' . $product_id);

        if($amount !== NULL && $amount != 0)
        {
            array_push($array, array(
                'product' => $product_id,
                'amount'  => (int) $amount,
            ));
        }
    }

    $expiresAt = now()->addMinutes(120);
    Cache::put('cc', $array, $expiresAt);
}
