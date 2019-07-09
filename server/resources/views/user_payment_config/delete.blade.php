@extends('layouts.master')

@section('content')

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
                    Delete Your Billing Card
                </div>

                <br/>

                <div class="card-text">
                    @include('layouts.errors')
                    <div class="row">

                        <div class="col-md-4 offset-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-text">
                                        <p class='text-center'><strong>Billing</strong></p>

                                        <p>{{ $billingCard->card_holder_name }}</p>
                                        <p>{{ $billingCard->card_number }}</p>
                                        <p>{{ $billingCard->expiry_date }}</p>

                                        <p class='text-center'><strong>Address</strong></p>
                                        <p>{{ $billingCard->building_name }}</p>
                                        <p>{{ $billingCard->street_address1 }}</p>
                                        <p>{{ $billingCard->street_address2 }}</p>
                                        <p>{{ $billingCard->street_address3 }}</p>
                                        <p>{{ $billingCard->street_address4 }}</p>
                                        <p>{{ $billingCard->county }}</p>
                                        <p>{{ $billingCard->city }}</p>
                                        <p>{{ $billingCard->postcode }}</p>
                                        <p>{{ $billingCard->country }}</p>
                                        <p>{{ $billingCard->formatted_phone_number }}</p>
                                        <p>{{ $billingCard->formatted_mobile_number }}</p>

                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <form class='form' action="{{ route('billingDestroy', $billingCard->id) }}" method='POST'>
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <div class="form-group">
                                            <label>Are you sure you want to delete this card?
                                                <select name="choice" class='form-control'>
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                            </label>
                                        </div>

                                        <div class="form-group">
                                            <input type="submit" class='form-control btn btn-primary'>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="float-left">
                        <button class='btn btn-default' onclick='history.go(-1);'>Back</button>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@stop

