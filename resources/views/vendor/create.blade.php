@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-4 offset-md-4">
            <form action="{{ route('vendorStore') }}" method="POST" class="card">
                {{ csrf_field() }}
                <div class="card-header">
                    <div class="lead">{{ $title }}</div>
                </div>
                <div class="card-body">
                    <div class="card-text">

                        <ul class='list-group'>
                            <li class="list-group-item">Use our secure ecommerce platform</li>
                            <li class='list-group-item'>Ship your items far and wide</li>
                            <li class="list-group-item">Sell your stuff</li>
                        </ul>

                        <br/>

                        @if(! $usersAddresses->isEmpty())
                            <select name="users_address" class="form-control">
                                <option value="0">
                                    Choose a company address
                                </option>
                                @foreach($usersAddresses as $usersAddress)


                                    <option value="{{ $usersAddress->id }}">
                                        {{ (string) $usersAddress }}
                                    </option>

                                @endforeach
                            </select>
                        @else
                            You must have at least one address on file.
                            <a href="{{ route('addressHome') }}">
                                Click here to add an address.
                            </a>
                        @endif

                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-left">
                        <input type="text" class='form-control' name="company_name" placeholder="Company Name">
                    </div>
                    <input type="submit" class="btn btn-primary float-right" value="Apply now">
                </div>
            </form>
        </div>
    </div>

@stop