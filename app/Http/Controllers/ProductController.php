<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\SessionCartHelper;
use App\Models\Product\Product;
use App\Models\Product\ProductReview;
use App\Models\User;

class ProductController extends Controller
{
    /** @property User */
    protected $user;

    /** @property SessionCartHelper */
    protected $sessionCartHelper;

    /** @property Product */
    protected $product;

    /** @property ProductReview */
    protected $productReviews;

    /**
     * @construct
     */
    public function __construct() {
        $this->user              = new User;
        $this->sessionCartHelper = new SessionCartHelper;
        $this->product           = new Product;
        $this->productReviews    = new ProductReview;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->products = $this->product
            ->getProducts($request)
            ->paginate(7)
            ->appends(request()
            ->except('page'));

        return view('product.index', [
                'title' => 'Products',
                'products' => $this->products,
                'input' => $request->all(),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Product $product)
    {
        $this->product = $product;
        
        if(Auth::check())
        {
            /** @var User */
            $this->user = auth()->user();

            if($this->user->id === $this->product->company->user_id)
                return redirect()
                    ->back()
                    ->with(
                        'flashDanger', 
                        'Unable to perform add to cart action on your own product.'
                    );

            $this->user->addProductToDbCart($this->product);
        }
        else
        {
            $this->sessionCartHelper->addProductToSessionCart($this->product);
        }

        return redirect()
            ->route('productShow', $this->product->id)
            ->with('flashSuccess', $this->product->name.' added to cart');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\r  $r
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        /** @var User */
        $this->user = auth()->user();
        $this->product = $product;
        $this->productReviews = $this->product->productReview()->get();

        $permissionToReview = FALSE;

        if(null !== $this->user) {
            $permissionToReview = $this->product->didUserPurchaseProduct($this->user->id);
        }

        return view('product.show', [
                'title' => $product->name,
                'product' => $this->product,
                'reviews' => $this->productReviews,
                'permissionToReview' => $permissionToReview,
            ]);
    }
}
