<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Product;

class SessionCart 
{
    /**
     * Adds a product to the user's session cart.
     * 
     * @param  \App\Product  $product
     */
    public static function addProductToSessionCart(Product $product)
    {
        /** Cookie will expire in 120 minutes */
        $expiresAt = now()->addMinutes(120);

        /** Get existing session cookie if set  */
        $sessionCart = Session::get('cc');

        /** Check is existing cookie is present */
        if($sessionCart !== NULL && is_array($sessionCart))
        {
            $productAlreadyAdded = FALSE;
            /** Check if this product already exists in cart */
            foreach($sessionCart as $cc)
            {
                if(is_array($cc) && in_array($product->id, $cc))
                {
                    $productAlreadyAdded = TRUE;
                }
            }
            /** Add new product to session cart if not already present */
            if($productAlreadyAdded == FALSE)
            {
                $newItem = array(
                    'product' => $product->id,
                    'amount'  => 1,
                );
                array_push($sessionCart, $newItem);
                Session::put('cc', $sessionCart, $expiresAt);
            }
        }
        else
        {
            /** Set a new session cart cookie with product as it's first item */
            $sessionCart = array(
                array(
                    'product' => $product->id,
                    'amount'  => 1,
                )
            );
            Session::put('cc', $sessionCart, $expiresAt);
        }
    }

    /**
     * Returns the user's session cart.
     * 
     * @return  array
     */
    public static function getSessionCart()
    {
        $sessionCart = Session::get('cc');
        $array = array();

        if(isset($sessionCart))
        {
            foreach($sessionCart as $cc)
            {
                $item = Product::where('id', $cc['product'])->first();
                $amount = $cc['amount'];

                array_push($array, array(
                    'product' => $item,
                    'amount'  => $amount
                ));
            }
        }

        return $array;
    }

    /**
     * Updates the respective number of products in the user's session cart.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public static function updateSessionCartAmount(Request $request)
    {
        /** Get existing session cart */
        $sessionCart = self::getSessionCart();
        $array     = array();

        foreach($sessionCart as $cc)
        {
            /** Check if an amount value for this product was given in the request */
            $product_id = $cc['product']->id;
            $amount = $request->get('amount-' . $product_id);

            if($amount !== NULL && $amount != 0)
            {
                /** Push to $array the product with new amount value */
                array_push($array, array(
                    'product' => $product_id,
                    'amount'  => (int) $amount,
                ));
            }
        }

        /** Set session cart equal to our updated products array */
        $expiresAt = now()->addMinutes(120);
        Session::put('cc', $array, $expiresAt);
    }

    /**
     * Remove the session cart cookie.
     */
    public static function clearSessionCart()
    {
        Session::forget('cc');
    }
}