<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use Predis\Client;


class HomeController extends Controller
{
    /**
     * @param Product $product
     * @param Client $client
     */
    public function __construct(
        protected Product $product = new Product,
        protected Client $client,
    ) {
        $this->client = new Client(config('database.redis.default.url'));
    }

    /**
     * Show the application dashboard.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->client->set("test", "apples");
        Log::debug($this->client->get("test"));

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
