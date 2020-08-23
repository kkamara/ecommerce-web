<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Helpers\CacheCart;
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
        return response()->json([
            "products" => Product::getProducts($request)->paginate(7), 
            "message" => "Successful",
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $user = \App\User::attemptAuth();

        if(null !== $user)
        {
            $user = auth()->user();

            if($user->id === $product->company->user_id)
            {
                return response()->json([
                    "errors" => ['Unable to perform add to cart action on your own product.'],
                    "message" => "Unauthorized"
                ], Response::HTTP_UNAUTHORIZED);
            }

            $user->addProductToDbCart($product);
        }
        else
        {
            CacheCart::addProductToCacheCart($product, $request->header("X-CLIENT-HASH-KEY"));
        }

        return response()->json([
            "message" => "Successful"
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\r  $r
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $user = \App\User::attemptAuth();
        $reviews = $product->productReview()->get();
        $permissionToReview = FALSE;
        $collection = compact('product', 'reviews');

        if(null !== $user) {
            $permissionToReview = $product->didUserPurchaseProduct($user->id);
            $collection['permissionToReview'] = $permissionToReview;
        }

        return response()->json([
            "product" => $collection,
            "message" => "Successful"
        ]);
    }
}
