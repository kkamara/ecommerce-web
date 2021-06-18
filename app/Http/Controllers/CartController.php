<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\SessionCartHelper;
use App\Models\Cart\Cart;
use App\Models\User;

class CartController extends Controller
{
    /** @property SessionCartHelper */
    protected $sessionCartHelper;

    /** @property User */
    protected $user;

    /** @property Cart */
    protected $cart;

    /**
     * @construct
     */
    public function __construct() {
        $this->sessionCartHelper = new SessionCartHelper;
        $this->user              = new User;
        $this->cart              = new Cart;
    }

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
            $this->user = auth()->user();
            $this->cart = $this->user->getDbCart();
        }
        else
        {
            $this->cart = $this->sessionCartHelper->getSessionCart();
        }

        return view('cart.show', [
                'title' => 'Cart',
                'cart' => $this->cart,
            ]);
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
            $this->user = auth()->user();
            $this->user->updateDbCartAmount($request);
        }
        else
        {
            $this->sessionCartHelper->updateSessionCartAmount($request);
        }

        return redirect()
            ->route('cartShow')
            ->with('flashSuccess', 'Your cart was successfully updated!');
    }
}
