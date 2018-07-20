@extends('layouts.master')

@section('content')

@include('layouts.errors')

<div class="row">
    <div class="col-md-12">

        <div class="card">

            <div class="card-body">
                <div class="lead">
                    For Your Reference: <span style='font-weight:700'>{{ $orderHistory->reference_number }}</span>
                </div>

                <br/>

                <div class="card-text">

                    <div class="row">

                        <div class="col-md-6" style='display:block;margin:0px auto;'>
                            <div class="text-center">
                                Your Purchased Items
                            </div>

                            <table class='table'>
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Shippable</th>
                                        <th>Delivery</th>
                                        <th>Amount</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderHistory->orderHistoryProducts as $product)
                                    <tr>
                                        <td>
                                            <a href='{{ route('productShow', $product->product['id']) }}'>
                                                {{ $product->product['name'] }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($product->shippable)
                                                Yes
                                            @else
                                            <strike>Yes</strike>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product->free_delivery)
                                                Free
                                            @else
                                                <strike>Free</strike>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $product['amount'] }}
                                        </td>
                                        <td>{{ $product->product['formatted_cost'] }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <th>Total:</th>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $orderHistory['amount_total'] }}</td>
                                        <td>{{ $orderHistory['formatted_cost'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-3" style='display:block;margin:0px auto;'>
                            <div class="text-center">
                                Invoice Address
                            </div>

                            <p>{{ $orderHistory->usersAddresses->building_name }}</p>
                            <p>{{ $orderHistory->usersAddresses->street_address1 }}</p>
                            <p>{{ $orderHistory->usersAddresses->street_address2 }}</p>
                            <p>{{ $orderHistory->usersAddresses->street_address3 }}</p>
                            <p>{{ $orderHistory->usersAddresses->street_address4 }}</p>
                            <p>{{ $orderHistory->usersAddresses->county }}</p>
                            <p>{{ $orderHistory->usersAddresses->city }}</p>
                            <p>{{ $orderHistory->usersAddresses->postcode }}</p>
                            <p>{{ $orderHistory->usersAddresses->country }}</p>
                            <p>{{ $orderHistory->usersAddresses->formatted_phone_number }}</p>
                            <p>{{ $orderHistory->usersAddresses->formatted_mobile_number }}</p>
                        </div>

                        <div class="col-md-3" style='display:block;margin:0px auto;'>
                            <div class="text-center">
                                Billing
                            </div>

                            <p>{{ $orderHistory->userPaymentConfig->card_holder_name }}</p>
                            <p>{{ $orderHistory->userPaymentConfig->card_number }}</p>
                            <p>{{ $orderHistory->userPaymentConfig->expiry_date }}</p>
                        </div>

                    </div>

                </div>
            </div>

            <div class="card-footer">
                <div class="float-right">
                    <button class='btn btn-primary' onclick='window.print();'>Print this page</button>
                </div>
            </div>

        </div>

    </div>

</div>

@stop

