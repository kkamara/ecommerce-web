@extends('layouts.master')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="col-md-4 offset-md-4">
                    @include('layouts.errors')

                    <form class='form' action="{{ route('registerCreate') }}" method='POST'>
                        {{ csrf_field() }}
                        {{ method_field('POST') }}

                        <div class="card-text">
                            <table>
                                <tr>
                                    <th width='150'></th>
                                </tr>
                                <tr>
                                    <th colspan='2' class='text-center'>
                                        <h5>Basic Details</h5>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='first_name'>First Name*:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='first_name'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='last_name'>Last Name*:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='last_name'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='email'>Email*:</label>
                                    </th>
                                    <td>
                                        <input type="email" class="form-control" name='email'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='email_confirm'>Confirm Email*:</label>
                                    </th>
                                    <td>
                                        <input type="email" class="form-control" name='email_confirm'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='password'>Password*:</label>
                                    </th>
                                    <td>
                                        <input type="password" class="form-control" name='password'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='password_confirm'>Confirm Password*:</label>
                                    </th>
                                    <td>
                                        <input type="password" class="form-control" name='password_confirm'>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan='2' class='text-center'>
                                        <h5>Delivery Address</h5>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='building_name'>Building Name/Number*:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='building_name'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='street_address1'>Street Address 1*:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='street_address1'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='street_address2'>Street Address 2:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='street_address2'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='street_address3'>Street Address 3:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='street_address3'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='street_address4'>Street Address 4:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='street_address4'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='postcode'>Postcode*:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='postcode'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='city'>City*:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='city'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='country'>Country*:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='country'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='phone_number_ext'>Phone Number Extension*:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='phone_number_ext'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='phone_number'>Phone Number*:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='phone_number'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='mobile_number_ext'>Mobile Number Extension:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='mobile_number_ext'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='mobile_number'>Mobile Number:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='mobile_number'>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan='2' class='text-center'>
                                        <h5>Billing Details</h5>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='card_holder_name'>Card Holder Name*:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='card_holder_name'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='card_number'>Card Number*:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='card_number'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='card_number'>Card Number*:</label>
                                    </th>
                                    <td>
                                        <input type="text" class="form-control" name='card_number'>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for='expiry_month'>Expiry Date*:</label>
                                    </th>
                                    <td>
                                        <input type="month" class="form-control" name='expiry_month'>
                                    </td>
                                </tr>
                            </table>
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
</div>

@stop