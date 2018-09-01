@extends('layouts.master')

@section('content')
<div class="card">

    <div class="card-header">
        <div class='lead'>{{ $title }}</div>
    </div>
    <div class="card-body">

        <div class="card-text">

            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <h5>Flagged Reviews</h5>
                    </div>

                    @if(!$unansweredFlaggedReviews->isEmpty())
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th width="15%">Name</th>
                                    <th width="15%">Date Flagged</th>
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
                                        <td>{{ $unansweredFlaggedReview->productReview->short_content }}</td>
                                        <td>
                                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#decideReviewModal-{{ $unansweredFlaggedReview->id }}">
                                                Make a Decision
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $unansweredFlaggedReviews->links() }}
                    @else
                        <div class="text-center">
                            <small>There are currently no flagged reviews.</small>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <h5>Vendor Applications</h5>
                    </div>

                    <div class="float-right">
                        <a href="" class="btn btn-sm btn-primary">View All Vendors</a>
                    </div>

                    @if(!$vendorApplications->isEmpty())
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Application Date</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendorApplications as $application)
                                    <tr>
                                        <td>{{ $application->user->name }}</td>
                                        <td>{{ $application->user->email }}</td>
                                        <td>{{ $application->created_at }}</td>
                                        <td>
                                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#decideVendorAppModal-{{ $application->id }}">
                                                Make a Decision
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $vendorApplications->links() }}
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

{{-- Modal for review action --}}
@foreach($unansweredFlaggedReviews as $unansweredFlaggedReview)
    <!-- Modal -->
    <div class="modal fade" id="decideReviewModal-{{ $unansweredFlaggedReview->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Flagged Review - Make a decision
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <table class='table'>
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td>{{ $unansweredFlaggedReview->productReview->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Date Flagged</th>
                                <td>{{ $unansweredFlaggedReview->created_at }}</td>
                            </tr>
                            <tr>
                                <th>Review</th>
                                <td>{{ $unansweredFlaggedReview->productReview->content }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- Modal for review action --}}
@foreach($vendorApplications as $vendorApplication)
    <!-- Modal -->
    <div class="modal fade" id="decideVendorAppModal-{{ $vendorApplication->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Vendor Application - Make a decision
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class='table'>
                    <tbody>
                        <tr>
                            <th>Name</th>
                            <td>{{ $vendorApplication->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $vendorApplication->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Application Date</th>
                            <td>{{ $vendorApplication->created_at }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
    </div>
@endforeach
@stop
