@extends('layouts.master')

@section('content')

@include('layouts.errors')

<div class="row">
    <div class="col-md-12">

        <div class="card">

            <div class="card-body">
                <div class="lead">
                    For Your Reference: {{ $orderHistory->reference_number }}
                </div>

                <br/>

                <div class="card-text">

                    <div class="row">

                        <div class="col-md-4 text-center">
                            Your Purchased Items

                            <table class='table'>
                                @foreach($orderHistory->orderHistoryProducts as $product)
                                <tr>
                                    <td></td>
                                </tr>
                                @endforeach
                            </table>
                        </div>

                        <div class="col-md-4 text-center">
                            Invoice Address
                        </div>

                        <div class="col-md-4 text-center">
                            Billing
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

@stop
