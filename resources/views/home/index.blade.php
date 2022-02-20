@extends('layouts.master')

@section('content')


    <div class="list-group">
        <div class="container">

        @forelse($products as $key => $product)

            <x-product-card :key="$key" :product="$product" />

        @empty
            <p>No products available.</p>
        @endforelse

        </div>
    </div>

    <br/>

    @if(!$products->isEmpty())

        <div class="text-center">
            {{ $products->links() }}
        </div>
    @endif

@stop
