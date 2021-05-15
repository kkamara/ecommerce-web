@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-4 offset-md-4">
                        <form class='form' action="{{ route('loginCreate') }}" method='POST'>
                            {{ csrf_field() }}
                            <div class="card-title">
                                <a href="{{ route('registerHome') }}" class="btn btn-info btn-sm">
                                    Click here to <strong>create an account</strong>
                                </a>
                            </div>
                            <div class="card-text">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                    @include('layouts.errors')

                                    <p>Logins</p>
                                    <ul>
                                        @foreach($logins as $role => $login)
                                            <li>{{$login['email']}}</li>
                                        @endforeach
                                    </ul>

                                    <br/>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input
                                            type="text"
                                            class='form-control'
                                            name='email'
                                            value="{{ $input['email'] ?? '' }}"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input
                                            type="password"
                                            class='form-control'
                                            name='password'
                                            value="secret"
                                        />
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

@section('scripts')
    <script>
        $(document).ready(() => {
            $('.role-tooltip').tooltip();
        });
    </script>
@stop
