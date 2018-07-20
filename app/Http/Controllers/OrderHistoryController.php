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
            $cart = $user->getDbCart();

            if(empty($cart)) return redirect()->back()->with('flashDanger', 'Please add an item to cart before checking out.');

            return view('order_history.create', array(
                'title' => 'Create Order',
                'cart' => $cart,
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
        $deliveryAddressIds = $billingCardIds = array();

        foreach($input as $k => $v)
        {
            if(strpos($k, 'address-') === 0)
            {
                $id = str_replace('address-', '', $k);

                if(is_numeric($id))
                    array_push($deliveryAddressIds, (int) $id);
            }
            elseif(strpos($k, 'card-') === 0)
            {
                $id = str_replace('card-', '', $k);

                if(is_numeric($id))
                    array_push($billingCardIds, (int) $id);
            }
        }

        $errors = $user->getOrderHistoryErrors(['delivery'=>$deliveryAddressIds, 'billing'=>$billingCardIds]);

        if(!empty($errors))
        {
            return redirect()->back()->with(compact('errors'));
        }

        if(request('cvc-'.$billingCardIds[0]) === NULL)
        {
            return redirect()->back()->with('errors', [
                'Missing cvc for chosen billing card.'
            ]);
        }

        // create order history
        $orderHistory = OrderHistory::create([
            'user_id' => $user->id,
            'reference_number' => OrderHistory::generateRefNum(),
            'cost' => (float) str_replace('£', '', Cart::price()),
            'user_payment_config_id' =>$billingCardIds[0],
            'users_addresses_id' => $deliveryAddressIds[0],
        ]);

        // create order history products
        foreach($user->getDbCart() as $cart)
        {
            for($i=0; $i < $cart['amount']; $i++)
            {
                OrderHistoryProducts::create([
                    'order_history_id' => $orderHistory->id,
                    'product_id' => $cart['product']->id,
                    'cost' => $cart['product']->cost,
                    'shippable' => $cart['product']->shippable,
                    'free_delivery' => $cart['product']->free_delivery,
                ]);
            }
        }

        $user->deleteDbCart();

        return view('order_history.store')
                ->with(compact('orderHistory'))
                ->withTitle('Invoice');
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
