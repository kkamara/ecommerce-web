<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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
        $query   = $request->input('query');
        $min     = $request->input('min_price');
        $max     = $request->input('max_price');
        $sort_by = $request->input('sort_by');

        $products = Product::select('products.id', 'products.name', 'products.user_id', 'products.company_id', 'products.short_description', 'products.long_description', 'products.product_details', 'products.image_path', 'products.cost', 'products.shippable', 'products.free_delivery', 'products.created_at', 'products.updated_at');
        $whereClause = array();

        if(isset($query))
        {
            $query = filter_var($query, FILTER_SANITIZE_STRING);
            array_push($whereClause, [
                'products.name', 'LIKE', "$query%"
            ]);
        }
        if(isset($min))
        {
            $min = filter_var($min, FILTER_SANITIZE_NUMBER_FLOAT);
            array_push($whereClause, [
                'products.cost', '>', $min
            ]);
        }
        if(isset($max))
        {
            $max = filter_var($max, FILTER_SANITIZE_NUMBER_FLOAT);
            array_push($whereClause, [
                'products.cost', '<', $max
            ]);
        }

        if(isset($whereClause)) $products = $products->where($whereClause);

        if(isset($sort_by))
        {
            if($sort_by == 'pop')
            {
                $products = $products->leftJoin('order_history_products', 'products.id', '=', 'order_history_products.product_id')
                    ->groupBy('order_history_products.product_id');
            }
            else
            {
                if($sort_by == 'top')
                {

                }
            }
        }

        $products = $products->orderBy('products.id', 'DESC')->paginate(7);

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
