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

        if(FlaggedProductReview::hasIpFlaggedThisReview($ip, $productReview->id)->isEmpty())
        {
            FlaggedProductReview::create([
                'product_reviews_id' => $productReview->id,
                'flagged_from_ip' => $ip,
            ]);

            return redirect()->back()->with('flashSuccess', 'Product review has been flagged and will be reviewed by moderators. Thanks!');
        }
        else
        {
            return redirect()->back()->with('flashDanger', 'You have already flagged that review.');
        }
    }
}
