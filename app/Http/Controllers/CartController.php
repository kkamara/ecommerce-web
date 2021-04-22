<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SessionCart;
use Auth;

class CartController extends Controller
{    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if(Auth::check())
        {
            $user = auth()->user();
            $cart = $user->getDbCart();
        }
        else
        {
            $cart = SessionCart::getSessionCart();
        }

        return view('cart.show', compact('cart'))->withTitle('Cart');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(Auth::check())
        {
            $user = auth()->user();
            $user->updateDbCartAmount($request);
        }
        else
        {
            SessionCart::updateSessionCartAmount($request);
        }

        return redirect()->route('cartShow')
                ->with('flashSuccess', 'Your cart was successfully updated!');
    }
}
