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
        $user = User::attemptAuth();
        $client_hash_key = $request->header("X-CLIENT-HASH-KEY");

        if(null === $user && null === $client_hash_key)
        {
            return abort(Response::HTTP_UNAUTHORIZED);
        }

        if($user)
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
     * @param  \App\Http\Requests\SanitiseRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(SanitiseRequest $request)
    {
        $user = User::attemptAuth();
        $client_hash_key = $request->header("X-CLIENT-HASH-KEY");

        if(null === $user && null === $client_hash_key)
        {
            return abort(Response::HTTP_UNAUTHORIZED);
        }

        if($user)
        {
            $user->updateDbCartAmount();
        }
        else
        {
            CacheCart::updateCacheCartAmount($request);
        }

        return response()->json(["message"=>"Successful"]);
    }
}
