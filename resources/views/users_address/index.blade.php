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
                    Addresses
                </div>

                <br/>

                <div class="card-text">

                    <div class="row">

                        @if($usersAddresses->isEmpty() === FALSE)

                            @foreach($usersAddresses as $address)
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-text">
                                                <p class='text-center'><strong>Address</strong></p>
                                                <p>{{ $address->building_name }}</p>
                                                <p>{{ $address->street_address1 }}</p>
                                                <p>{{ $address->street_address2 }}</p>
                                                <p>{{ $address->street_address3 }}</p>
                                                <p>{{ $address->street_address4 }}</p>
                                                <p>{{ $address->county }}</p>
                                                <p>{{ $address->city }}</p>
                                                <p>{{ $address->postcode }}</p>
                                                <p>{{ $address->country }}</p>
                                                <p>{{ $address->formatted_phone_number }}</p>
                                                <p>{{ $address->formatted_mobile_number }}</p>

                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="pull-left">
                                                <a href='{{ route('addressDelete', $address->id) }}' class='btn btn-danger'>Delete</a>
                                            </div>

                                            <div class="pull-right">
                                                <a href='{{ route('addressEdit', $address->id) }}' class='btn btn-info'>Edit Card</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        @else
                            <div class="col-md-12">
                                <p class='text-center'>You have no addresses</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('addressCreate') }}" class="btn btn-primary float-right">Add Address</a>
            </div>
        </div>

    </div>

</div>

@stop

