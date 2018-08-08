@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-4 offset-md-4">
            <div class="card">
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

                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('vendorStore') }}" class="btn btn-primary float-right">
                        Apply now
                    </a>
                </div>
            </div>
        </div>
    </div>

@stop