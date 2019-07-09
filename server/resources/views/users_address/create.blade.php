@extends('layouts.master')

@section('content')

<style>
    div.col-md-4 {
        margin-bottom:20px;
    }
    div.custom-div {
        border-right:1px solid lightgrey;
    }
</style>
<div class="row">
    <div class="col-md-12">

        <div class="card">

            <div class="card-body">
                <div class="lead">
                    Add Address
                </div>

                <br/>

                <div class="card-text">
                    <div class="row">
                        <div class="col-md-12">
                            @include('layouts.errors')
                            <form class='form' action="{{ route('addressStore') }}" method='POST'>
                                {{ csrf_field() }}
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-text">
                                            <div class="row">
                                                <div class="col-md-6 custom-div">
                                                    <p class='text-center'><strong>Address</strong></p>

                                                    <div class="form-group">
                                                        <label>Building Name / Number*:
                                                            <input class='form-control' name='building_name'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Street Address 1*:
                                                            <input class='form-control' name='street_address1'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Street Address 2:
                                                            <input class='form-control' name='street_address2'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Street Address 3:
                                                            <input class='form-control' name='street_address3'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Street Address 4:
                                                            <input class='form-control' name='street_address4'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>County:
                                                            <input class='form-control' name='county'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>City*:
                                                            <input class='form-control' name='city'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Postcode*:
                                                            <input class='form-control' name='postcode'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Country*:
                                                            <select name="country" class='form-control'>
                                                                <option value="">Choose Your Country</option>
                                                                @foreach(getCountriesList() as $shortName => $longName)
                                                                    @if(0)
                                                                        <option selected>
                                                                    @else
                                                                        <option value="{{ $longName }}">{{ $longName }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class='text-center'><strong>Contact Numbers</strong></p>

                                                    <div class="form-group">
                                                        <label>Phone Number Extension*:
                                                            <input class='form-control' name='phone_number_extension'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Phone Number*:
                                                            <input class='form-control' name='phone_number'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Mobile Number Extension:
                                                            <input class='form-control' name='mobile_number_extension'>
                                                        </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Mobile Number:
                                                            <input class='form-control' name='mobile_number'>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">

                                        <div class="form-group pull-left">
                                            <a href='{{ route('addressHome') }}' class='btn btn-secondary'>Back</a>
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

