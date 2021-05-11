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

            return response()->json(["message" => "Successful"]);
        }
        else
        {
            return response()->json([
                "errors" => ['You have already flagged that review.'],
                "message" => "Unsuccessful"
            ], config("app.http.bad_request"));
        }
    }
}
