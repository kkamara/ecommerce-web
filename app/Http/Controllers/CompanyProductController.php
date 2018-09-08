<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Company;
use App\Product;
use Validator;

class CompanyProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function index($slug, Request $request)
    {
        $company = Company::where('slug', $slug)->first();
        $user = auth()->user();

        if($user->hasRole('vendor') && $company !== NULL && $company->belongsToUser($user->id))
        {
            $companyProducts = Product::getProducts($request)->getCompanyProducts($company->id)->paginate(7);

            return view('company_product.index', [
                'title' => 'My Products',
                'companyProducts' => $companyProducts->appends(request()->except('page')),
                'input' => $request->all(),
            ]);
        }
        else
        {
            return abort(404);
        }
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $slug
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit($slug, Product $product, Request $request)
    {
        $company = Company::where('slug', $slug)->first();
        $user = auth()->user();

        if($user->hasRole('vendor') && $company !== NULL && $company->belongsToUser($user->id))
        {
            return view('company_product.edit', [
                'title' => 'Edit '.$product->name,
            ])->with(compact('product'));
        }
        else
        {
            return abort(404);
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
        $user = auth()->user();

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

                return redirect()->route('productShow', $product->id)->with('flashSuccess', 'Product details have been updated.');
            }
            else
            {
                return redirect()->back()->with([
                    'product' => $product,
                    'errors' => $errors,
                    'input' => $request->input(),
                ]);
            }
        }
        else
        {
            return abort(404);
        }
    }

    public function delete($slug, Product $product, Request $request)
    {
        $company = Company::where('slug', $slug)->first();
        $user = auth()->user();

        if($user->hasRole('vendor') && $company !== NULL && $company->belongsToUser($user->id))
        {
            return view('company_product.delete', [
                'title' => 'Delete '.$product->name,
            ])->with(compact('product'));
        }
        else
        {
            return abort(404);
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
        $user = auth()->user();

        if($user->hasRole('vendor') && $company !== NULL && $company->belongsToUser($user->id))
        {
            switch($request->input('choice'))
            {
                case '0':
                    return redirect()->route('productShow', $product->id)->with('flashSuccess', 'Your item listing has not been removed.');
                break;
                case '1':
                    $product->delete();

                    return redirect()->route('companyProductHome', $company->slug)->with('flashSuccess', 'Your item listing was successfully removed.');
                break;
                default:
                    return redirect()->back()->with('flashDanger', 'Oops, something went wrong. Contact system administrator.');
                break;
            }
        }
        else
        {
            return abort(404);
        }
    }
}
