<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product\Product;

class HomeController extends Controller
{
    /** @property Product */
    protected $product;

    /**
     * @construct
     */
    public function __construct() {
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
        $this->product = $this->product
            ->getProducts($request)
            ->paginate(7);

        return view('home.index', [
            'title' => 'Home',
            'products' => $this->product,
        ]);
    }
}
