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

                    <div class="card-text">
                        <h5>Basic Details</h5>
                        <hr/>

                        <div class='col-sm-8 offset-md-2'>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">First Name*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='first_name'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Last Name*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='last_name'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Email*:</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" name='email'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Password*:</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" name='password'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Confirm Password*:</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" name='password'>
                                </div>
                            </div>
                        </div>

                        <h5>Delivery Address</h5>
                        <hr/>

                        <div class='col-sm-8 offset-md-2'>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Building Number*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='building_name''>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Street Address 1*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='street_address1'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Street Address 2:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='street_address2'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Street Address 3:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='street_address3'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Street Address 4:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='street_address4'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Postcode*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='postcode'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">City*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='city'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Country*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='country'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Phone Number Extension*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='phone_number_ext'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Phone Number*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='phone_number'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Mobile Number Extension*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='mobile_number_ext'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Mobile Number*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='mobile_number'>
                                </div>
                            </div>
                        </div>


                        <h5>Billing Details</h5>
                        <hr/>

                        <div class='col-sm-8 offset-md-2'>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Card Holder Name*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='card_holder_name'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Card Number*:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name='card_number'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Expiry Date*:</label>
                                <div class="col-sm-9">
                                    <input type="month" class="form-control" name='expiry_date'>
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
