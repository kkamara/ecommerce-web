@if($key !== 0 && $key % 4 === 0) <div class='block'></div> @endif

<a 
    dusk="product-{{ $key + 1 }}"
    href="{{ $product->path }}" 
    class="list-group-item list-group-item-action product-card"
>
    <div class="w-100 justify-content-between">
        <img src="{{ $product->image_path }}" class='img-responsive'>
        
        <h5 class="mb-1 product-title">{{ $product->name }}</h5>
        <h3 class='product-price'>
            <strong>
                {{ $product->formatted_cost }}
            </strong>
        </h3>
    </div>
    <small>{{ $product->company->name }}.</small>
    <div class="float-right">
        @if($product->review !== '0.00') Rated {{ $product->review }} @endif
    </div>
</a>