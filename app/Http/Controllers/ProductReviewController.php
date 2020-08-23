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
        

        $user = \App\User::attemptAuth();

        if($product->didUserReviewProduct($user->id) === FALSE)
        {
            if($product->didUserPurchaseProduct($user->id))
            {
                $validator = Validator::make($request->all(), [
                    'rating' => 'required|integer|min:0|max:5',
                    'content' => 'max:600',
                ]);

                if(empty($validator->errors()->all()))
                {
                    $score = filter_var($request->input('rating'), FILTER_SANITIZE_NUMBER_INT);
                    $content = $request->input('content', FILTER_SANITIZE_STRING);

                    $data = array(
                        'user_id' => $user->id,
                        'score' => $score,
                        'content' => $content,
                    );

                    $product->productReview()->create($data);
                    $message = "Successful";

                    return response()->json(array_merge(
                        ["data" => $product], 
                        compact("message"),
                    ));
                }
                else
                {
                    return response()->json([
                        'error' => $validator->errors()->all(),
                        "message" => "Unsuccessful"
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
            else
            {
                return response()->json([
                    "message" => "Unauthorized"
                ], Response::HTTP_UNAUTHORIZED);
            }
        }
        else
        {
            return response()->json([
                'error' => [
                    "You have already reviewed this product."
                ],
                "message" => "Unsuccessful"
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
