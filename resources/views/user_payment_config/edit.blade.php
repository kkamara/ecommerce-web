@extends('layouts.master')

@section('content')

<style>
    div.col-md-4 {
        margin-bottom:20px;
    }
    div.custom-div {
        border-left:1px solid lightgrey;
        border-right:1px solid lightgrey;
    }
</style>
<div class="row">
    <div class="col-md-12">

        <div class="card">

            <div class="card-body">
                <div class="lead">
                    Edit Your Billing Card
                </div>

                <br/>

                <div class="card-text">
                    <div class="row">
                        <div class="col-md-12">
                            @include('layouts.errors')
                            <form class='form' action="{{ route('billingUpdate', $billingCard->id) }}" method='POST'>
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-text">
                                            <div class="row">
                                                <div class="col-md-4">

                                                    <p class='text-center'><strong>Billing</strong></p>

                                                    <div class="form-group">
                                                        <label>Card Holder Name*:
                                                            <input class='form-control' name='card_holder_name' value='{{ $billingCard->card_holder_name }}'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Card Number*:
                                                            <input class='form-control' name='card_number' value='{{ $billingCard->card_number }}'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Expiry Date*:
                                                            <input class='form-control' type='month' name='expiry_date' value='{{ $billingCard->edit_expiry_date }}'>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 custom-div">
                                                    <p class='text-center'><strong>Address</strong></p>

                                                    <div class="form-group">
                                                        <label>Building Name / Number*:
                                                            <input class='form-control' name='building_name' value='{{ $billingCard->building_name }}'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Street Address 1*:
                                                            <input class='form-control' name='street_address1' value='{{ $billingCard->street_address1 }}'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Street Address 2:
                                                            <input class='form-control' name='street_address2' value='{{ $billingCard->street_address2 }}'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Street Address 3:
                                                            <input class='form-control' name='street_address3' value='{{ $billingCard->street_address3 }}'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Street Address 4:
                                                            <input class='form-control' name='street_address4' value='{{ $billingCard->street_address4 }}'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>County:
                                                            <input class='form-control' name='county' value='{{ $billingCard->county }}'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>City*:
                                                            <input class='form-control' name='city' value='{{ $billingCard->city }}'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Postcode*:
                                                            <input class='form-control' name='postcode' value='{{ $billingCard->postcode }}'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Country*:
                                                            <select name="country" class='form-control'>
                                                                @foreach(\App\Helpers\getCountriesList() as $shortName => $longName)
                                                                    @if($billingCard->country === $longName)
                                                                        <option selected value="{{ $longName }}">{{ $longName }}</option>
                                                                    @else
                                                                        <option value="{{ $longName }}">{{ $longName }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class='text-center'><strong>Contact Numbers</strong></p>

                                                    <div class="form-group">
                                                        <label>Phone Number Extension*:
                                                            <input class='form-control' name='phone_number_extension' value='{{ $billingCard->phone_number_extension }}'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Phone Number*:
                                                            <input class='form-control' name='phone_number' value='{{ $billingCard->phone_number }}'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Mobile Number Extension:
                                                            <input class='form-control' name='mobile_number_extension' value='{{ $billingCard->mobile_number_extension }}'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Mobile Number:
                                                            <input class='form-control' name='mobile_number' value='{{ $billingCard->mobile_number }}'>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">

                                        <div class="form-group pull-left">
                                            <a href='{{ route('billingHome') }}' class='btn btn-secondary'>Back</a>
                                        </div>

                                        <div class="form-group pull-right">
                                            <input type="submit" class='btn btn-primary'>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@stop

