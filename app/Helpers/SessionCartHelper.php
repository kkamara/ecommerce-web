<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Product\Product;

class SessionCartHelper
{
    /**
     * @param String $key
     * @param Mixed $default (optional)
     * @return Mixed
     */
    public function get(string $key, $default = null): mixed {
        return Session::get($key, $default);
    }

    /**
     * @param String|Array $key
     * @param Mixed $value (optional)
     * @return Mixed
     */
    public function put(string $key, $value = null): mixed {
        return Session::put($key, $value);
    }

    /**
     * @param String|Array $keys
     * @return mixed
     */
    public function forget(string $keys): mixed {
        return Session::forget($keys);
    }

    /**
     * Adds a product to the user's session cart.
     * 
     * @param  \App\Models\Product  $product
     */
    public function addProductToSessionCart(Product $product)
    {
        /** Get existing session cookie if set  */
        $sessionCart = $this->get('cc');

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
                $this->put('cc', $sessionCart);
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
            $this->put('cc', $sessionCart);
        }
    }

    /**
     * Returns the user's session cart.
     * 
     * @return  array
     */
    public function getSessionCart()
    {
        $sessionCart = $this->get('cc');
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
    public function updateSessionCartAmount(Request $request)
    {
        /** Get existing session cart */
        $sessionCart = $this->getSessionCart();
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
        $this->put('cc', $array);
    }

    /**
     * Remove the session cart cookie.
     */
    public function clearSessionCart()
    {
        $this->forget('cc');
    }
}