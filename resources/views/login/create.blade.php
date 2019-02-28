@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-4 offset-md-4">
                        <div class="card-title">
                            <a href="{{ route('registerHome') }}" class="btn btn-info btn-sm">
                                Click here to <strong>create an account</strong>
                            </a>
                        </div>
                        <form class='form' action="{{ route('loginCreate') }}" method='POST'>
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="card-text">
                                @include('layouts.errors')

                                <div class="form-group">
                                    <div class="form-control">
                                        <label>Email
                                            <input type="text" class='form-control' name='email' value="{{ $input['email'] ?? '' }}">
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control">
                                        {{ csrf_field() }}
                                        <label>Password
                                            <input type="password" class='form-control' name='password'>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <input type="submit" class='btn btn-success pull-right' value='Login'>
                                <div class="clearfix"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop