<?php

namespace App\Http\Controllers;

use App\Http\Requests\SanitiseRequest;
use App\FlaggedProductReview;
use Illuminate\Http\Response;
use App\VendorApplication;
use App\ProductReview;
use App\UsersAddress;
use Carbon\Carbon;
use App\Company;
use App\User;

class ModeratorsHubController extends Controller
{
    /**
     * Display a listing of the resource..
     *
     * @param  SanitiseRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(SanitiseRequest $request)
    {        
        if((!$user = User::attemptAuth()) || !$user->hasRole('moderator')) 
        {
            return response()->json([
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            "data" => [
                'unansweredFlaggedReviews' => FlaggedProductReview::whereUnanswered()
                    ->paginate(5,  ['*'], 'flaggedReviewPage'),
                'vendorApplications' => VendorApplication::onlyNew()
                    ->paginate(5,  ['*'], 'vendorAppPage'), 
            ],
        ]);
    }

    /**
     * Moderator decides if flagged review requires deletion.
     *
     * @param ProductReview   $productReview
     * @param SanitiseRequest $request
     * @return \Illuminate\Http\Response
     */
    public function storeFlaggedReviewDecision(ProductReview $productReview, SanitiseRequest $request)
    {
        if((!$user = User::attemptAuth()) || !$user->hasRole('moderator')) 
        {
            return response()->json([
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED);
        }

        if( (bool) $request->input('accept') === TRUE ) /** if accepted */
        {
            FlaggedProductReview::where('product_reviews_id', $productReview->id)->delete();

            $productReview->update([
                'flagged_review_decided_by' => $user->id,
                'flagged_review_decision_reason' => $request->input('reason'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
        else /** if declined */
        {
            $productReview->update([
                'flagged_review_decided_by' => $user->id,
                'flagged_review_decision_reason' => $request->input('reason'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'deleted_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json(["message" => "Successful"]);
    }

    /**
     * Moderator decides if vendor application should be accepted.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeVendorApplicantDecision(VendorApplication $vendorApplication, SanitiseRequest $request)
    {
        if((!$user = User::attemptAuth()) || !$user->hasRole('moderator')) 
        {
            return response()->json([
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED);
        }

        if(
            $decisionError = FlaggedProductReview::getModDecisionError(
                $request->input('reason'), $request->input('accept'), $request->input('decline')
            )
        ) {
            return response()->json([
                "error"   => $decisionError,
                "message" => "Bad Request"
            ], Response::HTTP_BAD_REQUEST);
        }

        if( (bool) $request->input('accept') === TRUE ) /** if accepted */
        {
            $vendorApplicantsAddress = UsersAddress::find($vendorApplication->users_addresses_id);

            /** create vendor table row for user who made this application, moving across their details */
            Company::create([
                'slug' => str_slug($vendorApplication->proposed_company_name, '-'),
                'user_id' => $vendorApplication->user_id,
                'name' => $vendorApplication->proposed_company_name,
                'mobile_number' => $vendorApplicantsAddress->mobile_number ?: NULL,
                'mobile_number_extension' => $vendorApplicantsAddress->mobile_number_extension ?: NULL,
                'phone_number' => $vendorApplicantsAddress->phone_number,
                'phone_number_extension' => $vendorApplicantsAddress->phone_number_extension ?: NULL,
                'building_name' => $vendorApplicantsAddress->building_name,
                'street_address1' => $vendorApplicantsAddress->street_address1,
                'street_address2' => $vendorApplicantsAddress->street_address2 ?: NULL,
                'street_address3' => $vendorApplicantsAddress->street_address3 ?: NULL,
                'street_address4' => $vendorApplicantsAddress->street_address4 ?: NULL,
                'county' => $vendorApplicantsAddress->county ?: NULL,
                'country' => $vendorApplicantsAddress->country,
                'postcode' => $vendorApplicantsAddress->postcode,
            ]);

            /** update vendor_applications table row to accepted status */
            $vendorApplication->update([
                'decided_by' => $user->id,
                'accepted' => 1,
                'reason_given' => $request->input('reason_given'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            /** assign vendor role to the user who made the application */
            $vendorUser = User::find($vendorApplication->user_id)->assignRole('vendor');
        }
        else /** if declined */
        {
            $vendorApplication->update([
                'decided_by' => $user->id,
                'accepted' => 0,
                'reason_given' => $request->input('reason_given'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }

        return response()->json(["message" => "Successful"]);
    }
}
