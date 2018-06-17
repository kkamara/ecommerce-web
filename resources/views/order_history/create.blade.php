@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-8">
            <h3 class='lead'><strong>Checkout</strong></h3>
            <table class="table table-striped">
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
                            <input class='form-control' disabled type='number' name='amount-{{ $item['product']->id }}' value="{{ $item['amount'] }}"/>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('cartShow') }}" class="btn btn-primary">
                Edit your items
            </a>
            <br/>
            <br/>
            <h3 class='lead'><strong>Delivery Address</strong></h3>
            <h3 class='lead'><strong>Billing Card</strong></h3>
        </div>
        <div class="col-md-4">
            <ul class="list-group">
                <li class="list-group-item">
                    Charge: {{ $cartPrice }}
                </li>
                <li class="list-group-item">
                    <form method="POST" action="{{ route('orderStore') }}">
                        {{ csrf_field() }}
                        <input type='submit' value="Pay Your Order" class='btn btn-success' style='display:block;margin:0px auto;'>
                    </form>
                </li>
            </ul>
        </div>
    </div>

@stop
