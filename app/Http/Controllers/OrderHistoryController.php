<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Order\OrderHistoryProducts;
use App\Models\Order\OrderHistory;
use App\Models\Cart\Cart;
use App\Models\User;

class OrderHistoryController extends Controller
{
    /** @property User */
    protected $user;
    
    /** @property OrderHistoryProducts */
    protected $orderHistoryProducts;
    
    /** @property OrderHistory */
    protected $orderHistory;
    
    /** @property Cart */
    protected $cart;
    
    public function __construct() {
        $this->user                 = new User;
        $this->orderHistoryProducts = new OrderHistoryProducts;
        $this->orderHistory         = new OrderHistory;
        $this->cart                 = new Cart;
        $this->middleware('auth')->except(['create']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var User */
        $this->user = auth()->user();

        $this->orderHistory = $this->orderHistory->where([
            'user_id' => $this->user->id,
        ])->paginate(10);

        return view('order_history.index', [
            'title' => 'Invoices',
            'orderHistory' => $this->orderHistory, 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::check()) {
            /** @var User */
            $this->user = auth()->user();
            $this->cart = $this->user->getDbCart();

            if(empty($this->cart)) {
                return redirect()
                    ->back()
                    ->with(
                        'flashDanger', 
                        'Please add an item to cart before checking out.'
                    );
            }

            return view('order_history.create', array(
                'title' => 'Create Order',
                'cart' => $this->cart,
                'addresses' => $this->user->userAddress,
                'billingCards' => $this->user->userPaymentConfig,
            ));
        } else {
            return redirect('login/?fromOrder=true')
                ->with(
                    'flashDanger', 
                    'Please login or register to proceed to checkout'
                );
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
        /** @var User */
        $this->user = auth()->user();

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

        $errors = $this->user->getOrderHistoryErrors([
            'delivery'=>$deliveryAddressIds, 
            'billing'=>$billingCardIds,
        ]);

        if(!empty($errors))
        {
            return redirect()->back()->with(compact('errors'));
        }

        $billingCVC = request('cvc-'.$billingCardIds[0]);

        if($billingCVC === NULL) {
            return redirect()->back()->with('errors', [
                'Missing CVC for chosen billing card.'
            ]);
        } else if (strlen($billingCVC) !== 3) {
            return redirect()->back()->with('errors', [
                'CVC must be 3 characters long.'
            ]);
        }

        // create order history
        $this->orderHistory = $this->orderHistory->create([
            'user_id' => $this->user->id,
            'reference_number' => $this->orderHistory->generateRefNum(),
            'cost' => ((float) str_replace('£', '', $this->cart->price())) * 100,
            'user_payment_config_id' =>$billingCardIds[0],
            'users_addresses_id' => $deliveryAddressIds[0],
        ]);

        // create order history products
        foreach($this->user->getDbCart() as $this->cart)
        {
            $this->orderHistoryProducts->create([
                'order_history_id' => $this->orderHistory->id,
                'product_id' => $this->cart['product']->id,
                'amount' => $this->cart['amount'],
                'cost' => $this->cart['product']->cost,
                'shippable' => $this->cart['product']->shippable,
                'free_delivery' => $this->cart['product']->free_delivery,
            ]);
        }

        $this->user->deleteDbCart();

        return redirect()
            ->route('orderShow', $this->orderHistory->reference_number);
    }

    /**
     * Display the specified resource.
     *
     * @param  String $refNum
     * @return \Illuminate\Http\Response
     */
    public function show($refNum)
    {
        /** @var User */
        $this->user = auth()->user();

        $this->orderHistory = $this->orderHistory->where([
            'user_id'          => $this->user->id,
            'reference_number' => $refNum,
        ])->firstOrFail();

        return view('order_history.show', [
            'title'        => 'Invoice',
            'orderHistory' => $this->orderHistory,
        ]);
    }
}
