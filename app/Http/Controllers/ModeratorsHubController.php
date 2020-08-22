<?php

namespace App\Http\Controllers;

use App\FlaggedProductReview;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\VendorApplication;
use App\ProductReview;
use App\UsersAddress;
use App\Company;
use App\User;

class ModeratorsHubController extends Controller
{
    /**
     * Display a listing of the resource..
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $user = \App\User::attemptAuth();

        if($user->hasRole('moderator'))
        {
            $vendorApplications = VendorApplication::whereFresh()->paginate(5,  ['*'], 'vendorAppPage');
            $unansweredFlaggedReviews = FlaggedProductReview::whereUnanswered()->paginate(5,  ['*'], 'flaggedReviewPage');

            return response()->json(
                compact('vendorApplications', 'unansweredFlaggedReviews')
            );
        }
        else
        {
            return response()->json([
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Moderator decides if flagged review required deletion.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeFlaggedReviewDecision(ProductReview $productReview, Request $request)
    {
        $user = \App\User::attemptAuth();

        $reasonGiven     = filter_var($request->input('reason'), FILTER_SANITIZE_STRING);
        $acceptDecision  = filter_var($request->input('accept'), FILTER_SANITIZE_STRING);
        $declineDecision = filter_var($request->input('decline'), FILTER_SANITIZE_STRING);

        if($user->hasRole('moderator'))
        {
            if(! $decisionError = FlaggedProductReview::getModDecisionError($reasonGiven, $acceptDecision, $declineDecision))
            {
                FlaggedProductReview::where('product_reviews_id', $productReview->id)->delete();

                if( (bool) $acceptDecision === TRUE ) /** if accepted */
                {
                    $productReview->update([
                        'flagged_review_decided_by' => $user->id,
                        'flagged_review_decision_reason' => $reasonGiven,
                        'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    ]);
                }
                else /** if declined */
                {
                    $productReview->update([
                        'flagged_review_decided_by' => $user->id,
                        'flagged_review_decision_reason' => $reasonGiven,
                        'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                        'deleted_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    ]);
                }

                return response()->json(["message" => "Successful"]);
            }

            return response()->json(["message" => "Successful"]);
        }
        else
        {
            return response()->json([
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Moderator decides if vendor application should be accepted.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeVendorApplicantDecision(VendorApplication $vendorApplication, Request $request)
    {
        $user = \App\User::attemptAuth();

        $reasonGiven     = filter_var($request->input('reason_given'), FILTER_SANITIZE_STRING);
        $acceptDecision  = filter_var($request->input('accept'), FILTER_SANITIZE_STRING);
        $declineDecision = filter_var($request->input('decline'), FILTER_SANITIZE_STRING);

        if($user->hasRole('moderator'))
        {
            if(! $decisionError = VendorApplication::getModDecisionError($reasonGiven, $acceptDecision, $declineDecision))
            {
                if( (bool) $acceptDecision === TRUE ) /** if accepted */
                {
                    $vendorApplicantsAddress = UsersAddress::find($vendorApplication->users_addresses_id);

                    /** create vendor table row for user who made this application, moving across their details */
                    Company::create([
                        'slug' => str_slug($vendorApplication->proposed_company_name, '-'),
                        'user_id' => $vendorApplication->user_id,
                        'name' => $vendorApplication->proposed_company_name,
                        'mobile_number' => $vendorApplicantsAddress->mobile_number ?? NULL,
                        'mobile_number_extension' => $vendorApplicantsAddress->mobile_number_extension ?? NULL,
                        'phone_number' => $vendorApplicantsAddress->phone_number,
                        'phone_number_extension' => $vendorApplicantsAddress->phone_number_extension ?? NULL,
                        'building_name' => $vendorApplicantsAddress->building_name,
                        'street_address1' => $vendorApplicantsAddress->street_address1,
                        'street_address2' => $vendorApplicantsAddress->street_address2 ?? NULL,
                        'street_address3' => $vendorApplicantsAddress->street_address3 ?? NULL,
                        'street_address4' => $vendorApplicantsAddress->street_address4 ?? NULL,
                        'county' => $vendorApplicantsAddress->county ?? NULL,
                        'country' => $vendorApplicantsAddress->country,
                        'postcode' => $vendorApplicantsAddress->postcode,
                    ]);

                    /** update vendor_applications table row to accepted status */
                    $vendorApplication->update([
                        'decided_by' => $user->id,
                        'accepted' => 1,
                        'reason_given' => $reasonGiven,
                        'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    ]);

                    /** assign vendor role to the user who made the application */
                    $vendorUser = User::find($vendorApplication->user_id)->assignRole('vendor');
                }
                else /** if declined */
                {
                    $vendorApplication->update([
                        'decided_by' => $user->id,
                        'accepted' => 0,
                        'reason_given' => $reasonGiven,
                        'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                    ]);
                }
            }

            return response()->json(["message" => "Successful"]);
        }
        else
        {
            return response()->json([
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
