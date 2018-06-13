@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-8">
            <h3 class='lead'><strong>Your Cart</strong></h3>
            <hr/>
            @if(! empty($cart))
            <ul class="list-group">
                @foreach($cart as $k => $item)

                <li class='list-group-item'>
                    <a href="{{ route('productShow', $item['product']->product->id) }}">
                        {{ $item['product']->product->name }}
                    </a>

                    <span class='pull-right'>
                        <input class='form-control' type='number' value="{{ $item['amount'] }}"/>
                    </span>
                </li>
                <br/>
                @endforeach
            </ul>
            <br/>
            <div class="pull-right">
                <a href="" class="btn btn-primary">Update details</a>
            </div>
            @else
            <p>Your cart is empty.</p>
            @endif
        </div>
        <div class="col-md-4">
            <ul class="list-group">
                <li class="list-group-item">
                    <a href='{{ route('orderCreate') }}' class='btn btn-success' style='display:block;'>
                        Proceed to checkout
                    </a>
                </li>
            </ul>
        </div>
    </div>
@stop