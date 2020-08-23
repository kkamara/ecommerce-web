<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Requests\SanitiseRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;
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
    public function index(SanitiseRequest $request)
    {
        return response()->json([
            "data" => Product::getProducts($request)->paginate(7), 
            "message" => "Successful",
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SanitiseRequest $request, Product $product)
    {
        $user = \App\User::attemptAuth();

        if(null !== $user)
        {
            $user = auth()->user();

            if($user->id === $product->company->user_id)
            {
                return response()->json([
                    "error" => ['Unable to perform add to cart action on your own product.'],
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
        return response()->json([
            "data" => new ProductResource($product),
            "message" => "Successful"
        ]);
    }
}
