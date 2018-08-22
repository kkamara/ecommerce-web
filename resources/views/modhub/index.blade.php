@extends('layouts.master')

@section('content')
<style>
    .first-col {
        border-right:1px dashed black;
    }
</style>
<div class="card">

    <div class="card-header">
        <div class='lead'>{{ $title }}</div>
    </div>
    <div class="card-body">

        <div class="card-text">

            <div class="row">
                <div class="col-md-6 first-col">
                    <h6>Flagged Reviews</h6>
                    <hr/>

                    
                </div>
                <div class="col-md-6">
                    <div class="float-right">
                        <a href="" class="btn btn-sm btn-primary">View All Vendors</a>
                    </div>
                    <h6>Vendor Applications</h6>
                    <hr/>

                    @if(!$vendorApplications->isEmpty())
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Application Date</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendorApplications as $application)
                                    <tr>
                                        <td>{{ $application->user->name }}</td>
                                        <td>{{ $application->created_at }}</td>
                                        <td>
                                            <a href="" class="btn btn-sm btn-danger">Reject</a>
                                        </td>
                                        <td>
                                            <a href="" class="btn btn-sm btn-success">Accept</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center">
                            <small>There are currently no more vendor applications.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer"></div>
</div>

@stop
