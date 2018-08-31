<?php

namespace App\Http\Controllers;

use App\FlaggedProductReview;
use Illuminate\Http\Request;
use App\VendorApplication;
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
            $vendorApplications = VendorApplication::whereFresh()->paginate();
            $unansweredFlaggedReviews = FlaggedProductReview::whereUnanswered()->paginate();

            return view('modhub.index', [
                'title' => 'Moderator\'s Hub'
            ])->with(compact('vendorApplications', 'unansweredFlaggedReviews'));
        }
        else
        {
            return abort(404);
        }
    }
}
