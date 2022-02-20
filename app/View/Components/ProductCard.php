<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Product\Product;

class ProductCard extends Component
{
    /**
     * Create a new component instance.
     *
     * @param String $key
     * @param Product $product
     * @return void
     */
    public function __construct(public string $key, public Product $product) {}

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
