<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Models\Product\Product;

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
     * @param  string  $slug
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create($slug, Request $request)
    {
        $company = Company::where('slug', $slug)->first();
        $user = auth()->user();

        if($user->hasRole('vendor') && $company !== NULL && $company->belongsToUser($user->id))
        {
            return view('company_product.create', [
                'title' => 'Add a Product',
            ]);
        }
        else
        {
            return abort(404);
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
        $user = auth()->user();

        if($user->hasRole('vendor') && $company !== NULL && $company->belongsToUser($user->id))
        {
            $product = new Product;
            $errors = $product->getErrors($request);

            if(empty($errors))
            {
                /** @var string $imagePath */
                $imagePath = $product->uploadImage($request);
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
                    'image_path'        => $imagePath,
                );
                $product = $product->create($data);

                return redirect()
                    ->route('productShow', $product->id)
                    ->with('flashSuccess', 'Product has been added to your listings.');
            }
            else
            {
                return view('company_product.create', [
                    'title' => 'Add a Product',
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $slug
     * @param  \App\Models\Product $product
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
     * @param  \App\Models\Product $product
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
                /** @var string $imagePath */
                $imagePath = $product->uploadImage($request);

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
                    'image_path'        => $imagePath,
                );
                $product->update($data);

                return redirect()
                    ->route('productShow', $product->id)
                    ->with('flashSuccess', 'Product details have been updated.');
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
     * @param  \App\Models\Product $product
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

        return abort(404);
    }
}
