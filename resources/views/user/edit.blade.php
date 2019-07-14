@extends('layouts.master')

@section('content')
    <div class="row">
        <form class="col-md-12" action='' method='POST'>
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <div class="card">
                <div class="card-footer">
                    <div class="lead">Change Your Settings</div>
                </div>
                <div class="card-body">
                    <div class="card-text">
                        @include('layouts.errors')

                        <div class="row">
                            <div class="col-md-4 offset-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-text">

                                            <div class="form-group">
                                                <label>First Name:
                                                    <input type="text" name='first_name' class='form-control' value='{{ $user->first_name }}'>
                                                </label>
                                            </div>

                                            <div class="form-group">
                                                <label>Last Name:
                                                    <input type="text" name='last_name' class='form-control' value='{{ $user->last_name }}'>
                                                </label>
                                            </div>

                                            <div class="form-group">
                                                <label>Email:
                                                    <input type="email" name='email' class='form-control' value='{{ $user->email }}'>
                                                </label>
                                            </div>

                                            <div class="form-group">
                                                <label>Password*:
                                                    <input type="password" name='old_password' class='form-control'>
                                                </label>
                                            </div>

                                            <div class="form-group">
                                                <label>New Password:
                                                    <input type="password" name='new_password' class='form-control'>
                                                </label>
                                            </div>

                                            <div class="form-group">
                                                <label>Password Confirmation:
                                                    <input type="password" name='new_password_confirmation' class='form-control'>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer">
                    {{-- <div class="float-left">
                        <a href='{{ route('orderHome') }}' class='btn btn-secondary'>Back</a>
                    </div> --}}
                    <div class="float-right">
                        <button class='btn btn-primary'>Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
