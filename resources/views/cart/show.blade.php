@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-8">
            <h3 class='lead'><strong>Your Cart</strong></h3>
            @if(! empty($cart))
            <form action="{{ route('cartUpdate') }}" method='POST'>
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $k => $item)
                        <tr>
                            <td>
                                <a href="{{ route('productShow', $item['product']->id) }}">
                                    {{ $item['product']->name }}
                                </a>
                            </td>

                            <td>
                                {{ $item['product']->formatted_cost }}
                            </td>

                            <td>
                                <input class='form-control' type='number' name='amount-{{ $item['product']->id }}' value="{{ $item['amount'] }}"/>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <th>Total:</th>
                            <td>
                                {{ $cartPrice }}
                            </td>
                            <td>
                                {{ $cartCount }}
                            </td>
                        </tr>
                    </tbody>
                </table>
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