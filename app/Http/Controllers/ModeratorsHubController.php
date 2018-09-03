<?php

namespace App\Http\Controllers;

use App\FlaggedProductReview;
use Illuminate\Http\Request;
use App\VendorApplication;
use App\ProductReview;
use App\User;

class ModeratorsHubController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource..
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if($user->hasRole('moderator'))
        {
            $vendorApplications = VendorApplication::whereFresh()->paginate(5,  ['*'], 'vendorAppPage');
            $unansweredFlaggedReviews = FlaggedProductReview::whereUnanswered()->paginate(5,  ['*'], 'flaggedReviewPage');

            return view('modhub.index', [
                'title' => 'Moderator\'s Hub'
            ])->with(compact('vendorApplications', 'unansweredFlaggedReviews'));
        }
        else
        {
            return abort(404);
        }
    }

    /**
     * Moderator decides if flagged review required deletion.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeFlaggedReviewDecision(ProductReview $productReview, Request $request)
    {
        $user = auth()->user();

        if($user->hasRole('moderator'))
        {

        }
        else
        {
            return abort(404);
        }
    }

    /**
     * Moderator decides if vendor application should be accepted.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeVendorApplicantDecision(VendorApplication $vendorApplication, Request $request)
    {
        $user = auth()->user();

        if($user->hasRole('moderator'))
        {

        }
        else
        {
            return abort(404);
        }
    }
}
