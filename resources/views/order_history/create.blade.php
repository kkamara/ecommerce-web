@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <h3 class='lead'><strong>Checkout</strong></h3>
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
                                    <input class='form-control' disabled type='number' name='amount-{{ $item['product']->id }}' value="{{ $item['amount'] }}"/>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('cartShow') }}" class="btn btn-primary">
                    1 Edit your items
                    </a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <h3 class='lead'><strong>Select Delivery Address</strong></h3>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        @forelse($addresses as $address)
                        <div class="col-md-4">
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">

                                    <p>{{ $address['street_address1'] }}</p>
                                    <p>{{ $address['street_address2'] }}</p>
                                    <p>{{ $address['street_address3'] }}</p>
                                    <p>{{ $address['street_address4'] }}</p>
                                    <p>{{ $address['county'] }}</p>
                                    <p>{{ $address['city'] }}</p>
                                    <p>{{ $address['postcode'] }}</p>

                                    <div class="form-group">
                                        <label for="address-{{ $address['id'] }}">Choose this address</label>
                                        <input name='address-{{ $address['id'] }}' type="checkbox">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        You have no delivery addresses connected to your account. Click here to add a delivery address.
                        @endforelse
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <h3 class='lead'><strong>Select Billing Card</strong></h3>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        @forelse($billingCards as $card)
                        <div class="col-md-4">
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">

                                    <p>{{ $card['card_holder_name'] }}</p>
                                    <p>{{ $card['card_number'] }}</p>

                                    <div class="form-group">
                                        <label for="card{{ $card['id'] }}">Choose this card</label>
                                        <input name='card{{ $card['id'] }}' type="checkbox">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                            You have no billing cards connected to your account. Click here to add a billing card.
                        @endforelse
                    </div>
                </div>
            </div>
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
