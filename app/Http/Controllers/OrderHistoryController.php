<?php

namespace App\Http\Controllers;

use App\OrderHistoryProducts;
use Illuminate\Http\Request;
use App\OrderHistory;
use App\Cart;
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
        $user = \App\User::attemptAuth();

        $orderHistories = OrderHistory::where([
            'user_id' => $user->id,
        ])->paginate(10);

        return response()->json(compact('orderHistories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = \App\User::attemptAuth();

        $errors = $user->getOrderHistoryErrors($request);

        $message = "Unsuccessful";
        if(false == $errors->isEmpty())
        {
            $errors = $errors->all();

            return response()->json(
                compact('errors', "message"),
                config("app.http.bad_request")
            );
        }

        // create order history
        $orderHistory = OrderHistory::create([
            'user_id' => $user->id,
            'reference_number' => OrderHistory::generateRefNum(),
            'cost' => (float) str_replace('£', '', Cart::price()),
            'user_payment_config_id' => $request->billing_card_id,
            'users_addresses_id' => $request->address_id,
        ]);

        // create order history products
        foreach($user->getDbCart() as $cart)
        {
            OrderHistoryProducts::create([
                'order_history_id' => $orderHistory->id,
                'product_id' => $cart['product']->id,
                'amount' => $cart['amount'],
                'cost' => $cart['product']->cost,
                'shippable' => $cart['product']->shippable,
                'free_delivery' => $cart['product']->free_delivery,
            ]);
        }

        $user->deleteDbCart();

        return response()->json([
            "message" => "Successful"
        ], config("app.http.created"));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $refNum - Reference number for \App\OrderHistory resource.
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show($refNum, Request $request)
    {
        $user = \App\User::attemptAuth();

        $orderHistory = OrderHistory::where([
            'user_id' => $user->id,
            'reference_number' => $refNum,
        ])->firstOrFail();

        $message = "Unsuccessful";
        return response()->json(compact("orderHistory", "message"));
    }
}
