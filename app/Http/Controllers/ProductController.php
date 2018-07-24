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
        $query = $request->input('query');
        $min   = $request->input('min_price');
        $max   = $request->input('max_price');

        $products = new Product;
        $whereClause = array();

        if(isset($query))
        {
            array_push($whereClause, [
                'name', 'LIKE', "$query%"
            ]);
        }
        if(isset($min))
        {
            array_push($whereClause, [
                'cost', '>', $min
            ]);
        }
        if(isset($max))
        {
            array_push($whereClause, [
                'cost', '<', $max
            ]);
        }
// dd($whereClause);
        if(isset($whereClause)) $products = $products->where($whereClause);

        $products = $products->latest()->paginate(7);

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
            $user = auth()->user();
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
        return view('product.show', [
            'title' => $product->name
        ])->with(compact('product'));
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
