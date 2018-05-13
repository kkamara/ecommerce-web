@extends('layouts.master')

@section('content')


    <div class="list-group">

        @forelse($products as $product)

        <a href="{{ $product->slug }}" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
            <img style='max-height:100px' src="{{ $product->image_path }}" class='img-responsive'>
            <h5 class="mb-1">{{ $product->name }}</h5>
            <small>
                <span>
                    <strong>
                        {{ $product->cost }}
                    </strong>
                </span>
                <br/>
                <span>
                    Updated {{ $product->updated_at->diffForHumans() }}
                </span>
            </small>
            </div>
            <p class="mb-1">{{ $product->short_description }}.</p>
            <small>{{ $product->company->name }}.</small>
        </a>


    @empty
        <p>No products available.</p>
    @endforelse

    </div>

    <br/>

    @if(!$products->isEmpty())

        <div class="text-center">
            {{ $products->links() }}
        </div>
    @endif

@stop