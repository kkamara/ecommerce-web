<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::getProducts($request)->paginate(7);

        return view('product.index', [
            'title' => 'Products',
            'products' => $products->appends(request()->except('page')),
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
            $user = auth()->user();

            if($user->id === $product->company->user_id)
                return redirect()->back()->with('flashDanger', 'Unable to perform add to cart action on your own product.');

            $user->addProductToDbCart($product);
        }
        else
        {
            addProductToCacheCart($product);
        }

        return redirect()->route('productShow', $product->id)
                         ->with('flashSuccess', $product->name.' added to cart');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\r  $r
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $user = auth()->user();

        $reviews = $product->productReview()->get();

        $permissionToReview = FALSE;

        if(isset($user))
            $permissionToReview = $product->didUserPurchaseProduct($user->id);

        return view('product.show', [
            'title' => $product->name,
        ])
        ->with(compact('product'))
        ->with(compact('reviews'))
        ->with(compact('permissionToReview'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\r  $r
     * @return \Illuminate\Http\Response
     */
    public function edit(r $r)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\r  $r
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, r $r)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\r  $r
     * @return \Illuminate\Http\Response
     */
    public function destroy(r $r)
    {
        //
    }
}
