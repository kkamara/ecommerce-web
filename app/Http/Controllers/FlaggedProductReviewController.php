<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FlaggedProductReview;
use App\ProductReview;

class FlaggedProductReviewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductReview $productReview, Request $request)
    {
        $ip = $request->ip();

        if(FlaggedProductReview::hasIpFlaggedThisReview($ip)->isEmpty())
        {

        }
        else
        {
            return redirect()->back()->with('flashDanger', 'You\'ve already flagged that review.');
        }
    }
}
