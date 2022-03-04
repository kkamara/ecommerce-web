<?php

namespace App\Http\Controllers\Api\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Product\ProductRepository;

class ProductController extends Controller
{
    /**
     * @param ProductRepository $productRepository
    */
    public function __construct(
        protected ProductRepository $productRepository = new ProductRepository(),
    ) {}

    /**
     * @param Request $request
     * @return \Illuminate\Support\Response
     */
    public function index(Request $request)
    {
        return $this->productRepository->search($request->collect());
    }
}
