<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\User;

class ProductReviewController extends Controller
{
    /**
     * @constructor
     */
    public function __construct(protected ?User $user, protected ?Product $product) {
        $this->user    = new User;
        $this->product = new Product;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Product\Product $product
     * @param  \Illuminate\Http\Request    $request
     * @return \Illuminate\Http\Response
     */
    public function store(Product $product, Request $request)
    {
        /** @var User */
        $this->user = auth()->user();
        $this->product = $product;

        if(
            false == $this->product->didUserReviewProduct($this->user->id)
        ) {
            return redirect()
                ->back()
                ->with(
                    'flashDanger', 
                    'You have already reviewed this product.'
                );
        }

        if(false === $this->product->didUserPurchaseProduct($this->user))
        {
            return abort(404);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:0|max:5',
            'content' => 'max:600',
        ]);

        if(false === empty($validator->errors()->all()))
        {
            return redirect()->back()->with([
                'errors' => $validator->errors()->all(),
            ]);
        }

        $score = filter_var($request->input('rating'), FILTER_SANITIZE_NUMBER_INT);
        $content = $request->input('content', FILTER_SANITIZE_STRING);

        $data = array(
            'user_id' => $this->user->id,
            'score' => $score,
            'content' => $content,
        );
        $this->product->productReview()->create($data);

        return redirect()
            ->back()
            ->with(
                'flashSuccess', 
                'We appreciate your review of this item.'
            );
    }
}
