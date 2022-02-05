<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Predis\Client;
use App\Models\Product\Product;
use function App\Helpers\fingerprint;

class RedisCartHelper
{
    /**
     * Use caching service for querying guest user identifier
     * @param Client $client
     */
    public function __construct(protected ?Client $client = null) {
        $this->client = new Client(config('database.redis.default.url'));
    }

    /**
     * @param Mixed $key
     * @return Mixed
     */
    public function get($key): mixed {
        if (!$this->client->exists($key)) {
            return null;
        }
        $result = $this->client->get($key);
        return json_decode($result, 1);
    }

    /**
     * @param Mixed $key
     * @param Mixed $value
     * @return Mixed
     */
    public function set($key, $value): mixed {
        return $this->client->set(
            $key,
            json_encode($value),
            'EX', 
            config('session.lifetime'),
        );
    }

    /**
     * @param Array|String $keys
     * @return Integer
     */
    public function del(string $keys): int {
        if (!$this->client->exists($keys)) {
            return 0;
        }
        return $this->client->del($keys);
    }

    /**
     * @return Mixed
     */
    public function getRedisCart(): mixed {
        return $this->get($this->getGuestIdentifier());
    }

    /**
     * @return Mixed
     */
    public function getGuestIdentifier(): string {
        return fingerprint();
    }

    /**
     * @param Mixed $value
     * @return Mixed
     */
    private function setGuestIdentifier($value): mixed {
        return true;
    }

    /**
     * Adds a product to the user's session cart.
     * 
     * @param  \App\Models\Product  $product
     */
    public function addProductToSessionCart(Product $product)
    {
        /** Get existing session cookie if set  */
        $sessionCart = $this->get($this->getGuestIdentifier());

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
                $this->set($this->getGuestIdentifier(), $sessionCart);
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
            $this->set($this->getGuestIdentifier(), $sessionCart);
        }
    }

    /**
     * Returns the user's session cart.
     * 
     * @return  array
     */
    public function getSessionCart()
    {
        $sessionCart = $this->get($this->getGuestIdentifier());
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
        $this->set($this->getGuestIdentifier(), $array);
    }

    /**
     * Remove the session cart cookie.
     */
    public function clearSessionCart()
    {
        $this->del($this->getGuestIdentifier());
    }
}