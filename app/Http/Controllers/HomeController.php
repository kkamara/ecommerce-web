<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product\Product;

class HomeController extends Controller
{
    /**
     * @construct
     */
    public function __construct(protected ?Product $product) {
        $this->product = new Product;
    }

    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('home.index', [
            'title' => 'Home', 
            'products' => $this->product->getProducts(
                    $request->get('query'),
                    $request->get('sort_by'),
                    $request->get('min_price'),
                    $request->get('max_price'),
                )->paginate(7),
        ]);
    }
}
