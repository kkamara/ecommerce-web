@extends('layouts.master')

@section('content')

@include('layouts.errors')

<div class="row">
    <div class="col-md-12">

        <div class="card">

            <div class="card-body">
                <div class="lead">
                    Your Invoices
                </div>

                <br/>

                <div class="card-text">

                    <div class="row">

                        <div class="col-md-12">

                            @if($orderHistory->isEmpty() === FALSE)
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Reference Number</th>
                                            <th>Amount Purchased</th>
                                            <th>Cost</th>
                                            <th>Date Purchased</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orderHistory as $order)

                                            <tr>
                                                <td>
                                                    <a href='{{ route('orderShow', $order->reference_number) }}'>
                                                        {{ $order->reference_number }}
                                                    </a>
                                                </td>
                                                <td>{{ $order->amount_total }}</td>
                                                <td>{{ $order->formatted_cost }}</td>
                                                <td>{{ $order->created_at }}</td>
                                            </tr>

                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class='text-center'>You have no invoices</p>
                            @endif
                        </div>

                    </div>

                    <div class="text-center">
                        {{ $orderHistory->links() }}
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

@stop

