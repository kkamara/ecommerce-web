<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\SessionCartHelper;
use App\Models\Cart\Cart;
use App\Models\User;

class CartController extends Controller
{
    /**
     * @construct
     */
    public function __construct(
        protected ?SessionCartHelper $sessionCartHelper,
        protected ?User $user,
        protected ?Cart $cart,
    ) {
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
            /** @var User */
            $this->user = auth()->user();
            $cart = $this->user->getDbCart();
        }
        else
        {
            $cart = $this->sessionCartHelper->getSessionCart();
        }

        return view('cart.show', [
            'title' => 'Cart',
            'cart' => $cart,
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
            $this->user->updateDbCartAmount($request);
        }
        else
        {
            $this->sessionCartHelper->updateSessionCartAmount($request);
        }

        return redirect()
            ->route('cartShow')
            ->with('flashSuccess', config('flash.cart.update_success'));
    }
}
