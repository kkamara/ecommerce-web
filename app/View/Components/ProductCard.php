<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Product\Product;

class ProductCard extends Component
{
    /**
     * Create a new component instance.
     *
     * @param Int $key
     * @param Product $product
     * @return void
     */
    public function __construct(public int $key, public Product $product) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.product-card');
    }
}
