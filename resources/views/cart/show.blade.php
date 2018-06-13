@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-8">
            <h3 class='lead'><strong>Your Cart</strong></h3>
            <hr/>
            @if(! empty($cart))
            <form action="{{ route('cartUpdate') }}" method='POST'>
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                <ul class="list-group">
                    @foreach($cart as $k => $item)

                    <li class='list-group-item'>
                        <a href="{{ route('productShow', $item['product']->id) }}">
                            {{ $item['product']->name }}
                        </a>

                        <span class='pull-right'>
                            <input class='form-control' type='number' name='amount-{{ $item['product']->id }}' value="{{ $item['amount'] }}"/>
                        </span>
                    </li>
                    <br/>
                    @endforeach
                    <li class="list-group-item">
                        <div class="pull-right">
                            Total: {{ $cartCount }}
                        </div>
                    </li>
                </ul>
                <br/>
                <div class="pull-right">
                    <input type='submit' href="" class="btn btn-primary" value='Update details'>
                </div>
            </form>
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