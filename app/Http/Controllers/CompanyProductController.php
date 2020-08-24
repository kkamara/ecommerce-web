<?php

namespace App\Http\Controllers;

use App\Http\Requests\SanitiseRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Response;
use App\Company;
use App\Product;
use Validator;

class CompanyProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  string          $slug
     * @param  SanitiseRequest $request
     * @return Response
     */
    public function index($slug, SanitiseRequest $request)
    {
        $company = Company::where('slug', $slug)->firstOrFail();

        if(
            !$user = User::attemptAuth() || 
            !$user->hasRole('vendor') || 
            !$company || 
            !$company->belongsToUser($user->id)
        ) {
            return response()->json([
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'data' => Product::getProducts($request)
                ->getCompanyProducts($company->id)
                ->paginate(7),
            "message" => "Successful"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  string           $slug
     * @param  SanitiseRequest  $request
     * @return Response
     */
    public function store($slug, SanitiseRequest $request)
    {
        $company = Company::where('slug', $slug)->firstOrFail();

        if(
            !$user = User::attemptAuth() || 
            !$user->hasRole('vendor') || 
            !$company || 
            !$company->belongsToUser($user->id)
        ) {
            return response()->json([
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED);
        }

        $product = new Product;
        $errors = $product->getErrors($request);

        if(!empty($errors))
        {
            return response()->json([
                'error' => $errors,
                "message" => "Unsuccessful"
            ], Response::HTTP_BAD_REQUEST);
        }

        $useDefaultImage  = (bool) $request->input('use_default_image'); 

        if(!$useDefaultImage && Input::hasFile('image'))
        {
            $file = Input::file('image');
            $imageName = $file->getClientOriginalName();

            /** store image file if provided */
            if(isset($file) && isset($imageName))
            {
                $imagePath = 'uploads/companies/'.$company->id.'/images/';
                $file->move(public_path($imagePath), $imageName);

                $imagePath = '/uploads/companies/'.$company->id.'/images/'.$imageName;
            }
        }

        $newProductData = array(
            'user_id'           => $user->id,
            'company_id'        => $company->id,
            'name'              => $request->input('name'),
            'cost'              => number_format($request->input('cost')),
            'shippable'         => (bool) $request->input('shippable'),
            'free_delivery'     => (bool) $request->input('free_delivery'),
            'short_description' => $request->input('short_description'),
            'long_description'  => $request->input('long_description'),
            'product_details'   => $request->input('product_details'),
            'image_path'        => $imagePath ?: NULL,
        );
        $product = $product->create($newProductData);

        return response()->json([
            "message" => "Created"
        ], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string           $slug
     * @param  Product          $product
     * @param  SanitiseRequest  $request
     * @return Response
     */
    public function update($slug, Product $product, SanitiseRequest $request)
    {
        $company = Company::where('slug', $slug)->firstOrFail();

        if(
            !$user = User::attemptAuth() || 
            !$user->hasRole('vendor') || 
            !$company || 
            !$company->belongsToUser($user->id)
        ) {
            return response()->json([
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED);
        }

        $errors = $product->getErrors($request);

        if(!empty($errors))
        {
            return response()->json([
                'error' => $errors,
                "message" => "Bad Request"
            ], Response::HTTP_BAD_REQUEST);
        }

        $useDefaultImage  = filter_var($request->input('use_default_image'), FILTER_SANITIZE_NUMBER_INT);

        if(!$useDefaultImage && Input::hasFile('image'))
        {
            $file = Input::file('image');
            $imageName = $file->getClientOriginalName();

            /** store image file if provided */
            if(isset($file) && isset($imageName))
            {
                $imagePath = 'uploads/companies/'.$company->id.'/images/';
                $file->move(public_path($imagePath), $imageName);

                $imagePath = '/uploads/companies/'.$company->id.'/images/'.$imageName;
            }
        }

        $newProductData = array(
            'name'              => $request->input('name'),
            'cost'              => number_format($request->input('cost'), 2),
            'shippable'         => (bool) $request->input('shippable'),
            'free_delivery'     => (bool) $request->input('free_delivery'),
            'short_description' => $request->input('short_description'),
            'long_description'  => $request->input('long_description'),
            'product_details'   => $request->input('product_details'),
            'image_path'        => $imagePath ?: NULL,
        );
        $product->update($newProductData);

        return response()->json(["message" => 'Successful']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string          $slug
     * @param  Product         $product
     * @param  SanitiseRequest $request
     * @return Response
     */
    public function destroy($slug, Product $product, SanitiseRequest $request)
    {
        $company = Company::where('slug', $slug)->firstOrFail();
        
        if(
            (!$user = User::attemptAuth()) || 
            !$user->hasRole('vendor') || 
            !$company || 
            !$company->belongsToUser($user->id)
        ) {
            return response()->json([
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED);
        }

        switch($request->input('choice'))
        {
            case '0':
                $response = response()->json([
                    "message" => 'Successful',
                ]);
            break;
            case '1':
                $product->delete();

                $response = response()->json([
                    "message" => 'Successful',
                ]);
            break;
            default:
                $response = response()->json([
                    "message" => "Internal Server Error"
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            break;
        }

        return $response;
    }
}
