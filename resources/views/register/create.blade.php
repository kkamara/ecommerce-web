@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <form class='form' action="{{ route('registerCreate') }}" method='POST'>
                    {{ csrf_field() }}
                    {{ method_field('POST') }}

                    @if(Cache::get('errors') && false == Cache::get('errors')['basic']->isEmpty())

                        <ul class="list-group errors" style='list-style-type:none;margin:0px 0px 10px 0px;'>
                            @foreach(Cache::get('errors')['basic']->messages() as $errorName => $errorValue)
                                <li style="padding:5px 0px 5px 10px;" class="list-group-item-danger">{{ $errorValue[0] }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="card-text">
                        <h5>Basic Details</h5>
                        <hr/>
                        <small class='pull-right'>* = required</small>

                        <div class='col-sm-8 offset-md-2'>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">First Name*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['basic']->messages()['first_name'])) is-invalid @endif" name='first_name' value="@if(Cache::get('input') && Cache::get('input')['first_name']) {{Cache::get('input')['first_name']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Last Name*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['basic']->messages()['last_name'])) is-invalid @endif" name='last_name' value="@if(Cache::get('input') && Cache::get('input')['last_name']) {{Cache::get('input')['last_name']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Email*:</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['basic']->messages()['email'])) is-invalid @endif" name='email' value="@if(Cache::get('input') && Cache::get('input')['email']) {{Cache::get('input')['email']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Password*:</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['basic']->messages()['password'])) is-invalid @endif" name='password'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Confirm Password*:</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['basic']->messages()['password'])) is-invalid @endif" name='password'>
                                </div>
                            </div>
                        </div>

                        @if(Cache::get('errors') && false == Cache::get('errors')['address']->isEmpty())

                            <ul class="list-group errors" style='list-style-type:none;margin:0px 0px 10px 0px;'>
                                @foreach(Cache::get('errors')['address']->messages() as $errorName => $errorValue)
                                    <li style="padding:5px 0px 5px 10px;" class="list-group-item-danger">{{ $errorValue[0] }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <h5>Delivery Address</h5>
                        <hr/>

                        <div class='col-sm-8 offset-md-2'>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Building Number*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['address']->messages()['building_name'])) is-invalid @endif" name='building_name' value="@if(Cache::get('input') && Cache::get('input')['building_name']) {{Cache::get('input')['building_name']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Street Address 1*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['address']->messages()['street_address1'])) is-invalid @endif" name='street_address1' value="@if(Cache::get('input') && Cache::get('input')['street_address1']) {{Cache::get('input')['street_address1']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Street Address 2:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['address']->messages()['street_address2'])) is-invalid @endif" name='street_address2' value="@if(Cache::get('input') && Cache::get('input')['street_address2']) {{Cache::get('input')['street_address2']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Street Address 3:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['address']->messages()['street_address3'])) is-invalid @endif" name='street_address3' value="@if(Cache::get('input') && Cache::get('input')['street_address3']) {{Cache::get('input')['street_address3']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Street Address 4:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['address']->messages()['street_address4'])) is-invalid @endif" name='street_address4' value="@if(Cache::get('input') && Cache::get('input')['street_address4']) {{Cache::get('input')['street_address4']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Postcode*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['address']->messages()['postcode'])) is-invalid @endif" name='postcode' value="@if(Cache::get('input') && Cache::get('input')['postcode']) {{Cache::get('input')['postcode']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">City*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['address']->messages()['city'])) is-invalid @endif" name='city' value="@if(Cache::get('input') && Cache::get('input')['city']) {{Cache::get('input')['city']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Country*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['address']->messages()['country'])) is-invalid @endif" name='country' value="@if(Cache::get('input') && Cache::get('input')['country']) {{Cache::get('input')['country']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Phone Number Extension*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['address']->messages()['phone_number_ext'])) is-invalid @endif" name='phone_number_ext' value="@if(Cache::get('input') && Cache::get('input')['phone_number_ext']) {{Cache::get('input')['phone_number_ext']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Phone Number*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['address']->messages()['phone_number'])) is-invalid @endif" name='phone_number' value="@if(Cache::get('input') && Cache::get('input')['phone_number']) {{Cache::get('input')['phone_number']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Mobile Number Extension:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['address']->messages()['mobile_number_ext'])) is-invalid @endif" name='mobile_number_ext' value="@if(Cache::get('input') && Cache::get('input')['mobile_number_ext']) {{Cache::get('input')['mobile_number_ext']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Mobile Number:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['address']->messages()['mobile_number'])) is-invalid @endif" name='mobile_number' value="@if(Cache::get('input') && Cache::get('input')['mobile_number']) {{Cache::get('input')['mobile_number']}} @endif">
                                </div>
                            </div>
                        </div>

                        @if(Cache::get('errors') && false == Cache::get('errors')['billing']->isEmpty())

                            <ul class="list-group errors" style='list-style-type:none;margin:0px 0px 10px 0px;'>
                                @foreach(Cache::get('errors')['billing']->messages() as $errorName => $errorValue)
                                    <li style="padding:5px 0px 5px 10px;" class="list-group-item-danger">{{ $errorValue[0] }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <h5>Billing Details</h5>
                        <hr/>

                        <div class='col-sm-8 offset-md-2'>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Card Holder Name*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['billing']->messages()['card_holder_name'])) is-invalid @endif" name='card_holder_name' value="@if(Cache::get('input') && Cache::get('input')['card_holder_name']) {{Cache::get('input')['card_holder_name']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Card Number*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['billing']->messages()['card_number'])) is-invalid @endif" name='card_number' value="@if(Cache::get('input') && Cache::get('input')['card_number']) {{Cache::get('input')['card_number']}} @endif">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Expiry Date*:</label>
                                <div class="col-sm-9">
                                    <input type="month" class="form-control @if(Cache::get('errors') && isset(Cache::get('errors')['billing']->messages()['expiry_date'])) is-invalid @endif" name='expiry_date' value="@if(Cache::get('input') && Cache::get('input')['expiry_date']) {{Cache::get('input')['expiry_date']}} @endif">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <input type="submit" class='btn btn-success pull-right' value='Register'>
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop
