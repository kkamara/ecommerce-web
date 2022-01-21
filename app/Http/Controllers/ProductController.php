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
    /**
     * @param ?User $user
     * @param SessionCarthelper $sessionCartHelper
     * @param Product $product
     * @param ProductReview $productReviews
     * @return void
     */
    public function __construct(
        protected ?User $user = new User,
        protected SessionCartHelper $sessionCartHelper = new SessionCartHelper,
        protected Product $product = new Product,
        protected ProductReview $productReviews = new ProductReview,
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = $this->product->getProducts(
            $request->get('query'),
            $request->get('sort_by'),
            $request->get('min_price'),
            $request->get('max_price'),
        )
            ->paginate(7)
            ->appends(request()
            ->except('page'));

        return view('product.index', [
                'title' => 'Products',
                'products' => $products,
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
        if(Auth::check())
        {
            /** @var User */
            $this->user = auth()->user();

            if($this->user->id === $product->company->user_id)
                return redirect()
                    ->back()
                    ->with(
                        'flashDanger', 
                        config('flash.product.create_danger'),
                    );

            $this->user->addProductToDbCart($product);
        }
        else
        {
            $this->sessionCartHelper->addProductToSessionCart($product);
        }

        return redirect()
            ->route('productShow', $product->id)
            ->with('flashSuccess', sprintf(config('flash.product.create_success'), $product->name));
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
        $productReviews = $product->productReview()->get();

        $permissionToReview = FALSE;

        if(null !== $this->user) {
            $permissionToReview = $product->didUserPurchaseProduct($this->user);
        }

        return view('product.show', [
                'title' => $product->name,
                'product' => $product,
                'reviews' => $productReviews,
                'permissionToReview' => $permissionToReview,
            ]);
    }
}
