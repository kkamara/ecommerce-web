<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Helpers\RedisCartHelper;

class HomeController extends Controller
{
    /**
     * @param Product $product
     * @param RedisCartHelper $redisClient
     */
    public function __construct(
        protected Product $product = new Product,
        protected RedisCartHelper $redisClient = new RedisCartHelper,
    ) {}

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
