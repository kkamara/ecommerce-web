<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Company;
use App\Product;
use Validator;

class CompanyProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function index($slug, Request $request)
    {
        $company = Company::where('slug', $slug)->first();        

        $user = \App\User::attemptAuth();

        if($user->hasRole('vendor') && $company !== NULL && $company->belongsToUser($user->id))
        {
            $companyProducts = Product::getProducts($request)->getCompanyProducts($company->id)->paginate(7);

            return response()->json([
                'companyProducts' => $companyProducts,
                "message" => "Successful"
            ]);
        }
        else
        {
            return response()->json([
                'message' => "Unauthorized",
            ], config("app.http.unauthorized"));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  string  $slug
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($slug, Request $request)
    {
        $company = Company::where('slug', $slug)->first();
        

        $user = \App\User::attemptAuth();

        if($user->hasRole('vendor') && $company !== NULL && $company->belongsToUser($user->id))
        {
            $product = new Product;
            $errors = $product->getErrors($request);

            if(empty($errors))
            {
                $useDefaultImage  = filter_var($request->input('use_default_image'), FILTER_SANITIZE_NUMBER_INT);

                if(Input::hasFile('image'))
                {
                    $file = Input::file('image');
                    $imageName = $file->getClientOriginalName();
                }

                /** store image file if provided */
                if(isset($file) && isset($imageName))
                {
                    $imagePath = 'uploads/companies/'.$company->id.'/images/';
                    $file->move(public_path($imagePath), $imageName);

                    $imagePath = '/uploads/companies/'.$company->id.'/images/'.$imageName;
                }

                /**
                 * on the following line we use FILTER_SANITIZE_STRING on an expected float,
                 * this is because FILTER_SANITIZE_NUMBER_FLOAT produces unexpected results
                 */
                $data = array(
                    'user_id'           => $user->id,
                    'company_id'        => $company->id,
                    'name'              => filter_var($request->input('name'), FILTER_SANITIZE_STRING),
                    'cost'              => number_format(filter_var($request->input('cost'), FILTER_SANITIZE_STRING), 2),
                    'shippable'         => (bool) filter_var($request->input('shippable'), FILTER_SANITIZE_NUMBER_INT),
                    'free_delivery'     => (bool) filter_var($request->input('free_delivery'), FILTER_SANITIZE_NUMBER_INT),
                    'short_description' => filter_var($request->input('short_description', FILTER_SANITIZE_STRING)),
                    'long_description'  => filter_var($request->input('long_description', FILTER_SANITIZE_STRING)),
                    'product_details'   => filter_var($request->input('product_details'), FILTER_SANITIZE_STRING),
                    'image_path'        => $imagePath ?? NULL,
                );
                $product = $product->create($data);

                return response()->json([
                    "message" => "Successful"
                ], config("app.http.created"));
            }
            else
            {
                return response()->json([
                    'errors' => $errors,
                    "message" => "Unsuccessful"
                ], config("app.http.bad_request"));
            }
        }
        else
        {
            return response()->json([
                "message" => "Unauthorized"
            ], config("app.http.unauthorized"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update($slug, Product $product, Request $request)
    {
        $company = Company::where('slug', $slug)->first();
        

        $user = \App\User::attemptAuth();

        if($user->hasRole('vendor') && $company !== NULL && $company->belongsToUser($user->id))
        {
            $errors = $product->getErrors($request);

            if(empty($errors))
            {
                $useDefaultImage  = filter_var($request->input('use_default_image'), FILTER_SANITIZE_NUMBER_INT);

                if(Input::hasFile('image'))
                {
                    $file = Input::file('image');
                    $imageName = $file->getClientOriginalName();
                }

                /** store image file if provided */
                if(isset($file) && isset($imageName))
                {
                    $imagePath = 'uploads/companies/'.$company->id.'/images/';
                    $file->move(public_path($imagePath), $imageName);

                    $imagePath = '/uploads/companies/'.$company->id.'/images/'.$imageName;
                }

                /**
                 * on the following line we use FILTER_SANITIZE_STRING on an expected float,
                 * this is because FILTER_SANITIZE_NUMBER_FLOAT produces unexpected results
                 */
                $data = array(
                    'name'              => filter_var($request->input('name'), FILTER_SANITIZE_STRING),
                    'cost'              => number_format(filter_var($request->input('cost'), FILTER_SANITIZE_STRING), 2),
                    'shippable'         => (bool) filter_var($request->input('shippable'), FILTER_SANITIZE_NUMBER_INT),
                    'free_delivery'     => (bool) filter_var($request->input('free_delivery'), FILTER_SANITIZE_NUMBER_INT),
                    'short_description' => filter_var($request->input('short_description', FILTER_SANITIZE_STRING)),
                    'long_description'  => filter_var($request->input('long_description', FILTER_SANITIZE_STRING)),
                    'product_details'   => filter_var($request->input('product_details'), FILTER_SANITIZE_STRING),
                    'image_path'        => $imagePath ?? NULL,
                );
                $product->update($data);

                return response()->json(["message" => 'Successful']);
            }
            else
            {
                return response()->json([
                    'errors' => $errors,
                    "message" => "Unsuccessful"
                ], config("app.http.bad_request"));
            }
        }
        else
        {
            return response()->json([
                "message" => "Unauthorized"
            ], config("app.http.unauthorized"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $slug
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug, Product $product, Request $request)
    {
        $company = Company::where('slug', $slug)->first();        

        $user = \App\User::attemptAuth();

        if($user->hasRole('vendor') && $company !== NULL && $company->belongsToUser($user->id))
        {
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
                        "message" => "Unsuccessful"
                    ], config("app.http.internal_error"));
                break;
            }

            return $response;
        }
        else
        {
            $response = response()->json([
                "message" => "Unauthorized"
            ], config("app.http.unauthorized"));
        }
    }
}
