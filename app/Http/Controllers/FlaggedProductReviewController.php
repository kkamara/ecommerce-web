<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product\FlaggedProductReview;
use App\Models\Product\ProductReview;

class FlaggedProductReviewController extends Controller
{
    /**
     * @param FlaggedProductReview $flaggedProductReview
     * @return void
     */
    public function __construct(protected FlaggedProductReview $flaggedProductReview = new FlaggedProductReview) {}

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
                    config('flash.flagged_product_review.store_success'),
                );
        }
        
        return redirect()
            ->back()
            ->with(
                'flashDanger', 
                config('flash.flagged_product_review.store_danger'),
            );
    }
}
