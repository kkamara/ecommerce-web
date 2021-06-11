<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\SessionCartHelper;
use App\Models\Product\Product;
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
            SessionCartHelper::addProductToSessionCart($product);
        }

        return redirect()
            ->route('productShow', $product->id)
            ->with('flashSuccess', $product->name.' added to cart');
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
            ->with(compact(
                'product',
                'reviews',
                'permissionToReview',
            ));
    }
}
