@extends('layouts.master')

@section('content')

@include('layouts.errors')
<style>
    div.col-md-4 {
        margin-bottom:20px;
    }
</style>
<div class="row">
    <div class="col-md-12">

        <div class="card">

            <div class="card-body">
                <div class="lead">
                    Billing Cards
                </div>

                <br/>

                <div class="card-text">

                    <div class="row">

                        @if($billingCards->isEmpty() === FALSE)

                            @foreach($billingCards as $card)
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-text">
                                                <p class='text-center'><strong>Billing</strong></p>

                                                <p>{{ $card->card_holder_name }}</p>
                                                <p>{{ $card->card_number }}</p>
                                                <p>{{ $card->expiry_date }}</p>

                                                <p class='text-center'><strong>Address</strong></p>
                                                <p>{{ $card->building_name }}</p>
                                                <p>{{ $card->street_address1 }}</p>
                                                <p>{{ $card->street_address2 }}</p>
                                                <p>{{ $card->street_address3 }}</p>
                                                <p>{{ $card->street_address4 }}</p>
                                                <p>{{ $card->county }}</p>
                                                <p>{{ $card->city }}</p>
                                                <p>{{ $card->postcode }}</p>
                                                <p>{{ $card->country }}</p>
                                                <p>{{ $card->formatted_phone_number }}</p>
                                                <p>{{ $card->formatted_mobile_number }}</p>

                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="pull-left">
                                                <a href='{{ route('billingDelete', $card->id) }}' class='btn btn-danger'>Delete</a>
                                            </div>

                                            <div class="pull-right">
                                                <a href='{{ route('billingEdit', $card->id) }}' class='btn btn-primary'>Edit Card</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        @else
                            <div class="col-md-12">
                                <p class='text-center'>You have no billing cards</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@stop

