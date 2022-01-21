<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Models\Product\FlaggedProductReview;
use App\Models\Company\VendorApplication;
use App\Models\Product\ProductReview;
use App\Models\User\UsersAddress;
use App\Models\Company\Company;
use App\Models\User;
use Carbon\Carbon;

class ModeratorsHubController extends Controller
{    
    /**
     * @param Carbon $carbon
     * @param ?User $user
     * @param FlaggedProductReview $flaggedProductReview
     * @param VendorApplication $vendorApplication
     * @param ProductReview $productReview
     * @param UsersAddress $usersAddress
     * @param Company $company
     * @return void
     */
    public function __construct(
        protected Carbon $carbon = new Carbon,
        protected ?User $user = new User,
        protected FlaggedProductReview $flaggedProductReview = new FlaggedProductReview,
        protected VendorApplication $vendorApplication = new VendorApplication,
        protected ProductReview $productReview = new ProductReview,
        protected UsersAddress $usersAddress = new UsersAddress,
        protected Company $company = new Company,
    ) {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource..
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var User */
        $user = auth()->user();
        if(false === $user->hasRole('moderator'))
        {
            return abort(404);
        }

        $vendorApplications = $this->vendorApplication
            ->whereFresh()
            ->paginate(
                5,
                ['*'],
                'vendorAppPage'
            );
        $unansweredFlaggedReviews = $this->flaggedProductReview
            ->whereUnanswered()
            ->paginate(
                5, 
                ['*'],
                'flaggedReviewPage'
            );
        
        $title = 'Moderator\'s Hub';

        return view('modhub.index', compact(
            'title', 
            'vendorApplications', 
            'unansweredFlaggedReviews',
        ));
    }

    /**
     * Moderator decides if flagged review required deletion.
     *
     * @param \App\Models\Product\ProductReview $productReview
     * @param \Illuminate\Http\Request          $request
     * @return \Illuminate\Http\Response
     */
    public function storeFlaggedReviewDecision(ProductReview $productReview, Request $request)
    {
        /** @var User */
        $this->user = auth()->user();

        $reasonGiven     = filter_var($request->input('reason'), FILTER_SANITIZE_STRING);
        $acceptDecision  = filter_var($request->input('accept'), FILTER_SANITIZE_STRING);
        $declineDecision = filter_var($request->input('decline'), FILTER_SANITIZE_STRING);

        if(! $this->user->hasRole('moderator'))
        {
            return abort(404);
        }

        if(
            $decisionError = $this->flaggedProductReview->getModDecisionError(
                $reasonGiven, 
                $acceptDecision, 
                $declineDecision
            )
        ) {
            return redirect()->back()->with('flashSuccess', $decisionError);
        }

        $this->flaggedProductReview->where(
            'product_reviews_id', 
            $productReview->id
        )->delete();

        /** @var Array stores flash success / failure msg to display to user */
        $flashMessage = '';

        if( (bool) $acceptDecision === TRUE ) /** if accepted */
        {
            $productReview->update([
                'flagged_review_decided_by' => $this->user->id,
                'flagged_review_decision_reason' => $reasonGiven,
                'updated_at' => $this->carbon->now()->format('Y-m-d H:i:s'),
            ]);

            $flashMessage = ['flashSuccess' => config('flash.moderators_hub.store_flagged_review_decision_success')];
        }
        else /** if declined */
        {
            $productReview->update([
                'flagged_review_decided_by' => $this->user->id,
                'flagged_review_decision_reason' => $reasonGiven,
                'updated_at' => $this->carbon->now()->format('Y-m-d H:i:s'),
                'deleted_at' => $this->carbon->now()->format('Y-m-d H:i:s'),
            ]);

            $flashMessage = ['flashDanger' => config('flash.moderators_hub.store_flagged_review_decision_danger')];
        }

        return redirect()
            ->route('modHubHome')
            ->with($flashMessage);
    }

    /**
     * Moderator decides if vendor application should be accepted.
     *
     * @param \App\Models\Company\VendorApplication $vendorApplication
     * @param \IlluminateHttp\Request               $request
     * @return \Illuminate\Http\Response
     */
    public function storeVendorApplicantDecision(VendorApplication $vendorApplication, Request $request) {
        /** @var User */
        $this->user = auth()->user();

        $reasonGiven     = filter_var($request->input('reason_given'), FILTER_SANITIZE_STRING);
        $acceptDecision  = filter_var($request->input('accept'), FILTER_SANITIZE_STRING);
        $declineDecision = filter_var($request->input('decline'), FILTER_SANITIZE_STRING);

        if(! $this->user->hasRole('moderator'))
        {
            return abort(404);
        }

        if(
            $decisionError = $vendorApplication->getModDecisionError(
                $reasonGiven, 
                $acceptDecision, 
                $declineDecision
            )
        ) {
            return redirect()->back()->with('flashSuccess', $decisionError);
        }

        if( (bool) $acceptDecision === TRUE ) /** if accepted */
        {
            $vendorApplicantsAddress = $this->usersAddress->find(
                $vendorApplication->users_addresses_id
            );

            /** Creates db record vendor application user */
            $this->company->create([
                'slug' => Str::slug($vendorApplication->proposed_company_name, '-'),
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
                'decided_by' => $this->user->id,
                'accepted' => 1,
                'reason_given' => $reasonGiven,
                'updated_at' => $this->carbon->now()->format('Y-m-d H:i:s'),
            ]);

            /** assign vendor role to the user who made the application */
            $this->user
                ->find($vendorApplication->user_id)
                ->assignRole('vendor');

            $flashMessage = ['flashSuccess' => config('flash.moderators_hub.store_vendor_application_decision_success')];
        }
        else /** if declined */
        {
            $vendorApplication->update([
                'decided_by' => $this->user->id,
                'accepted' => 0,
                'reason_given' => $reasonGiven,
                'updated_at' => $this->carbon->now()->format('Y-m-d H:i:s'),
            ]);

            $flashMessage = ['flashDanger' => config('flash.moderators_hub.store_vendor_application_decision_danger')];
        }

        return redirect()->route('modHubHome')->with($flashMessage);
    }
}
