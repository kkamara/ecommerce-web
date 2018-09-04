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

        $reasonGiven     = filter_var($request->input('reason'), FILTER_SANITIZE_STRING);
        $acceptDecision  = filter_var($request->input('accept'), FILTER_SANITIZE_STRING);
        $declineDecision = filter_var($request->input('decline'), FILTER_SANITIZE_STRING);

        if($user->hasRole('moderator'))
        {
            if(! $decisionError = FlaggedProductReview::getError($reasonGiven, $acceptDecision, $declineDecision))
            {
                FlaggedProductReview::where('product_reviews_id', $productReview->id)->delete();

                if( (bool) $acceptDecision === TRUE )
                {
                    $productReview->update([
                        'flagged_review_decided_by' => $user->id,
                        'flagged_review_decision_reason' => $reasonGiven,
                        'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    ]);

                    $flashMessage = "Review has been restored successfully.";
                }
                else
                {
                    $productReview->update([
                        'flagged_review_decided_by' => $user->id,
                        'flagged_review_decision_reason' => $reasonGiven,
                        'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                        'deleted_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    ]);

                    $flashMessage = "Review has been deleted successfully.";
                }

                return redirect()->route('modHubHome')->with('flashSuccess', $flashMessage);
            }
            else
            {
                return redirect()->back()->with('flashSuccess', $decisionError);
            }
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
