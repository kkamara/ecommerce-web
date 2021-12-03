<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product\FlaggedProductReview;
use App\Models\Product\ProductReview;

class FlaggedProductReviewController extends Controller
{
    public function __construct(protected FlaggedProductReview $flaggedProductReview) {
        $this->flaggedProductReview = new FlaggedProductReview;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Product\ProductReview $productReview
     * @param  \Illuminate\Http\Request          $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductReview $productReview, Request $request)
    {
        $ip = $request->ip();

        if(
            $this->flaggedProductReview->hasIpFlaggedThisReview(
                $ip, 
                $productReview->id,
            )->isEmpty()
        ) {
            $this->flaggedProductReview->create([
                'product_reviews_id' => $productReview->id,
                'flagged_from_ip'    => $ip,
            ]);

            return redirect()
                ->back()
                ->with(
                    'flashSuccess', 
                    'Product review has been flagged and will be reviewed by moderators. Thanks!'
                );
        }
        
        return redirect()
            ->back()
            ->with(
                'flashDanger', 
                'You have already flagged that review.'
            );
    }
}
