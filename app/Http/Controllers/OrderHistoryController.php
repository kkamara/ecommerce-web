<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrderHistory;
use Auth;

class OrderHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->middleware('auth')->except(['create']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::check())
        {
            $user = auth()->user();
            $cachCart = $user->getDbCart();

            return view('order_history.create', array(
                'title' => 'Create Order',
                'cart' => $cachCart,
                'addresses' => $user->userAddress,
                'billingCards' => $user->userPaymentConfig,
            ));
        }
        else
        {
            return redirect('login/?fromOrder=true')->with('flashDanger', 'Please login or register to proceed to checkout');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $input = $request->request;
        $deliveryAddresses = $billingCards = array();

        foreach($input as $k => $v)
        {
            if(strpos($k, 'address-') === 0)
            {
                $id = str_replace('address-', '', $k);

                if(is_numeric($id))
                    array_push($deliveryAddresses, (int) $id);
            }
            elseif(strpos($k, 'card-') === 0)
            {
                $id = str_replace('card-', '', $k);

                if(is_numeric($id))
                    array_push($billingCards, (int) $id);
            }
        }

        $errors = $user->getOrderHistoryErrors(['delivery'=>$deliveryAddresses, 'billing'=>$billingCards]);

        if(!empty($errors))
        {
            return redirect()->back()->with(compact('errors'));
        }

        // create order history

        // create order history products
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OrderHistory  $orderHistory
     * @return \Illuminate\Http\Response
     */
    public function show(OrderHistory $orderHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrderHistory  $orderHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderHistory $orderHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrderHistory  $orderHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderHistory $orderHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrderHistory  $orderHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderHistory $orderHistory)
    {
        //
    }
}
