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

                    @if(!$unansweredFlaggedReviews->isEmpty())
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Date Flagged</th>
                                    <th>Review</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unansweredFlaggedReviews as $unansweredFlaggedReview)
                                    <tr>
                                        <td>{{ $unansweredFlaggedReview->productReview->user->name }}</td>
                                        <td>{{ $unansweredFlaggedReview->created_at }}</td>
                                        <td>{{ $unansweredFlaggedReview->productReview->content }}</td>
                                        <td>
                                            <a href="" class="btn btn-sm btn-danger">Delete Review</a>
                                        </td>
                                        <td>
                                            <a href="" class="btn btn-sm btn-success">Accept Review</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center">
                            <small>There are currently no flagged reviews.</small>
                        </div>
                    @endif
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
                            <small>There are currently no vendor applications.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer"></div>
</div>

<!-- Modal -->
<div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        ...
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
    </div>
    </div>
</div>
</div>

@stop
