@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                @include('layouts.errors')

                <form class='form' action="{{ route('registerCreate') }}" method='POST'>
                    {{ csrf_field() }}
                    {{ method_field('POST') }}
                    <div class="row">
                        <div class="col-md-4">

                            <div class="card-text">
                                <h5>Basic Details</h5>
                                <hr/>
                                <div class="text-center">
                                    <div class="form-group">
                                        <label>First Name*:
                                            <input value='text' type="text" class="form-control" name='first_name'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Last Name*:
                                            <input value='text' type="text" class="form-control" name='last_name'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Email*:
                                            <input value='user@mail.com' type="email" class="form-control" name='email'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Password*:
                                            <input value='password' type="password" class="form-control" name='password'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Confirm Password*:
                                            <input value='password' type="password" class="form-control" name='password_confirmation'>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card-text">
                                <h5>Delivery Address</h5>
                                <hr/>

                                <div class="text-center">
                                    <div class="form-group">
                                        <label>Building Name/Number*:
                                            <input value='text' type="text" class="form-control" name='building_name'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Street Address 1*:
                                            <input value='text' type="text" class="form-control" name='street_address1'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Street Address 2:
                                            <input type="text" class="form-control" name='street_address2'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Street Address 3:
                                            <input type="text" class="form-control" name='street_address3'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Street Address 4:
                                            <input type="text" class="form-control" name='street_address4'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Postcode*:
                                            <input value='postcode' type="text" class="form-control" name='postcode'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>City*:
                                            <input value='text' type="text" class="form-control" name='city'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Country*:
                                            <input value='text' type="text" class="form-control" name='country'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Phone Number Extension*:
                                            <input value='text' type="text" class="form-control" name='phone_number_ext'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Phone Number*:
                                            <input value='111111' type="text" class="form-control" name='phone_number'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Mobile Number Extension:
                                            <input type="text" class="form-control" name='mobile_number_ext'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Mobile Number:
                                            <input type="text" class="form-control" name='mobile_number'>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card-text">
                                <h5>Billing Details</h5>
                                <hr/>
                                <div class="text-center">
                                    <div class="form-group">
                                        <label>Card Holder Name*:
                                            <input value='card holder name' type="text" class="form-control" name='card_holder_name'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Card Number*:
                                            <input value='1111222233334444' type="text" class="form-control" name='card_number'>
                                        </label>
                                    </div>

                                    <div class="form-group">
                                        <label>Expiry Date*:
                                            <input type="month" class="form-control" name='expiry_date'>
                                        </label>
                                    </div>
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