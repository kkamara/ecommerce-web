<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SessionCart;
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
        
        if(null !== $user)
        {
            $cart = $user->getDbCart();
        }
        else
        {
            $cart = SessionCart::getSessionCart();
        }

        if(false == $cart) 
        {
            $cart = array();
        }

        $message = "Successful";
        return response()->json(compact('cart', "message"));
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
            SessionCart::updateSessionCartAmount($request);
        }

        return response()->json(["message"=>"Successful"]);
    }
}
