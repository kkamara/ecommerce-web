@extends('layouts.master')

@section('content')

@include('layouts.errors')

<div class="row">
    <div class="col-md-12">

        <div class="lead">
            For Your Reference: {{ $orderHistory->reference_number }}
        </div>

    </div>
</div>

@stop
