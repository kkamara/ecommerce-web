<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\SanitiseRequest;
use App\ProductReview;
use App\Product;
use Validator;

class ProductReviewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SanitiseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Product $product, SanitiseRequest $request)
    {
        if(
            (!$user = User::attemptAuth()) || 
            !$product->didUserPurchaseProduct($user->id)
        ) {
            return response()->json([
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED);
        }

        if($product->didUserReviewProduct($user->id))
        {
            return response()->json([
                'error' => "You have already reviewed this product.",
                "message" => "Unsuccessful"
            ], Response::HTTP_BAD_REQUEST);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:0|max:5',
            'content' => 'max:600',
        ]);

        if(!empty($validator->errors()->all()))
        {
            return response()->json([
                'error' => $validator->errors()->all(),
                "message" => "Unsuccessful"
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = array(
            'user_id' => $user->id,
            'score' => $request->input('rating'),
            'content' => $request->input('content'),
        );

        $product->productReview()->create($data);

        return response()->json([
            "message" => "Successful",
            "data" => $product,
        ]);
    }
}
