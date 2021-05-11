<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CacheCart;
use App\Cart;
use Auth;

class CartController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = \App\User::attemptAuth();

        if(null === $user && !empty($token))
        {
            return redirect()->route("login");
        }

        $client_hash_key = $request->get("client_hash_key");

        if(null !== $user)
        {
            $cart = $user->getDbCart();
        }
        else
        {
            if ($client_hash_key === null) {
                return response()->json([
                    "message" => "Client hash key not given"
                ], 409);
            }
            $cart = CacheCart::getCacheCart($client_hash_key);
        }

        if(false == $cart)
        {
            $cart = array();
        }

        $cost = Cart::price($client_hash_key);
        $count = Cart::count($client_hash_key);

        $message = "Successful";
        return response()->json(compact("cart", "cost", "count", "message"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = \App\User::attemptAuth();

        if(null === $user && !empty($token))
        {
            return redirect()->route("login");
        }

        if(null !== $user)
        {
            $user->updateDbCartAmount($request);
        }
        else
        {
            CacheCart::updateCacheCartAmount($request);
        }

        return response()->json(["message"=>"Successful"]);
    }
}
