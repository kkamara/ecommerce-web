<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product\Product;
use Validator;

class ProductReviewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Product $product, Request $request)
    {
        $user = auth()->user();

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

                    return redirect()->back()->with('flashSuccess', 'We appreciate your review of this item.');
                }
                else
                {
                    return redirect()->back()->with([
                        'errors' => $validator->errors()->all(),
                    ]);
                }
            }
            else
            {
                return abort(404);
            }
        }
        
        return redirect()->back()->with('flashDanger', 'You have already reviewed this product.');
    }
}
