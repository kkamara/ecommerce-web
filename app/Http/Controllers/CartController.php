<?php

namespace App\Http\Controllers;

use App\Http\Requests\SanitiseRequest;
use Illuminate\Http\Response;
use App\Helpers\CacheCart;
use App\User;
use App\Cart;
use Auth;

class CartController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Requests\SanitiseRequest $request
     * @return \Illuminate\Http\Response
     */
    public function show(\App\Http\Requests\SanitiseRequest $request)
    {
        $client_hash_key = $request->header('X_CLIENT_HASH_KEY');
        
        if($user = User::attemptAuth())
        {
            $cart = $user->getDbCart();
        }
        else
        {
            if (!$client_hash_key) {
                return response()->json([
                    "message" => "Client hash key not given"
                ], 409);
            }
            $cart = CacheCart::getCacheCart($client_hash_key);
        }

        if(false == $cart) $cart = array();

        $cost = Cart::price($client_hash_key);
        $count = Cart::count($client_hash_key);

        $message = "Successful";
        return response()->json(
            array_merge(
                ["data" => compact("cart", "cost", "count")], 
                compact("message"),
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SanitiseRequest $request
     * @return \Illuminate\Http\Response
    */
    public function update(SanitiseRequest $request)
    {
        if($user = User::attemptAuth())
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
