<?php

namespace App\Http\Controllers;

use App\Http\Requests\SanitiseRequest;
use App\FlaggedProductReview;
use Illuminate\Http\Response;
use App\ProductReview;

class FlaggedProductReviewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SanitiseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductReview $productReview, SanitiseRequest $request)
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
                "error" => 'You have already flagged that review.',
                "message" => "Unsuccessful"
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
