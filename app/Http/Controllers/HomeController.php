<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product\Product;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::getProducts($request)->paginate(7);

        return view('home.index')
            ->withTitle('Home')
            ->with(compact('products'));
    }
}
