<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Validator;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
        else
        {
            return redirect()->back()->with('flashDanger', 'You have already reviewed this product.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
