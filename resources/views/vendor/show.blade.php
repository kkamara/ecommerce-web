@extends('layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <div class="lead">{{ $title }}</div>
                </div>
                <div class="card-body">
                    <div class="card-text">

                        <div class="text-center">
                            <span class='lead btn btn-success'>You've successfully applied to become a vendor.</span>
                        </div>

                    </div>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
    </div>

@stop