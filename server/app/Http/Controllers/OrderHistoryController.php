<?php

namespace App\Http\Controllers;

use App\OrderHistoryProducts;
use Illuminate\Http\Request;
use App\OrderHistory;
use App\Cart;
use Auth;

class OrderHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['create']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $orderHistory = OrderHistory::where([
            'user_id' => $user->id,
        ])->paginate(10);

        return view('order_history.index', [
            'title' => 'Invoices'
        ])->with(compact('orderHistory'));
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

        $billingCVC = $billingCardIds[0];

        if(request('cvc-'.$billingCVC) === NULL)
        {
            return redirect()->back()->with('errors', [
                'Missing CVC for chosen billing card.'
            ]);
        } else if (strlen($billingCVC) !== 3) {
            return redirect()->back()->with('errors', [
                'CVC must be 3 characters long.'
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

        return redirect()->route('orderShow', $orderHistory->reference_number);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OrderHistory  $orderHistory
     * @return \Illuminate\Http\Response
     */
    public function show($refNum)
    {
        $user = auth()->user();

        $orderHistory = OrderHistory::where([
            'user_id' => $user->id,
            'reference_number' => $refNum,
        ])->firstOrFail();

        return view('order_history.show')
                ->with(compact('orderHistory'))
                ->withTitle('Invoice');
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
