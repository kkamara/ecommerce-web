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

        if(
            null === $user->hasRole('vendor') || 
            null === $company || 
            false === $company->belongsToUser($user->id)
        ) {
            return abort(404);
        }

        $companyProducts = Product::getProducts($request)
            ->getCompanyProducts($company->id)
            ->paginate(7)
            ->appends(request()
            ->except('page'));

        return view('company_product.index', [
            'title' => 'My Products',
            'companyProducts' => $companyProducts,
            'input' => $request->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function create($slug)
    {
        $company = Company::where('slug', $slug)->first();
        $user = auth()->user();

        if(
            null === $user->hasRole('vendor') || 
            null === $company || 
            false === $company->belongsToUser($user->id)
        ) {
            return abort(404);
        }

        return view('company_product.create', [
            'title' => 'Add a Product',
        ]);
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

        if(
            false === $user->hasRole('vendor') || 
            null === $company || 
            false === $company->belongsToUser($user->id)
        ) {
            return abort(404);
        }

        $product = new Product;
        $errors = $product->getErrors($request);

        if(0 < sizeof($errors))
        {
            return view('company_product.create', [
                'title' => 'Add a Product',
                'errors' => $errors,
                'input' => $request->input(),
            ]);
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
            'image_path'        => $product->uploadImage($request),
        );
        $product = $product->create($data);

        return redirect()
            ->route('productShow', $product->id)
            ->with('flashSuccess', 'Product has been added to your listings.');
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

        if(
            false === $user->hasRole('vendor') || 
            null === $company || 
            false === $company->belongsToUser($user->id)
        ) {
            return abort(404);
        }

        return view('company_product.edit', [
            'title' => 'Edit '.$product->name,
        ])->with(compact('product'));
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

        if(
            false === $user->hasRole('vendor') || 
            null === $company ||
            false === $company->belongsToUser($user->id)
        ) {
            return abort(404);
        }

        $errors = $product->getErrors($request);
        if(0 < sizeof($errors))
        {
            return redirect()->back()->with([
                'product' => $product,
                'errors' => $errors,
                'input' => $request->input(),
            ]);
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
            'image_path'        => $product->uploadImage($request),
        );
        $product->update($data);

        return redirect()
            ->route('productShow', $product->id)
            ->with('flashSuccess', 'Product details have been updated.');
    }

    public function delete($slug, Product $product, Request $request)
    {
        $company = Company::where('slug', $slug)->first();
        $user = auth()->user();

        if(
            false === $user->hasRole('vendor') || 
            null === $company || 
            false === $company->belongsToUser($user->id)
        ) {
            return abort(404);
        }

        return view('company_product.delete', [
            'title' => 'Delete '.$product->name,
        ])->with(compact('product'));
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

        if(
            false === $user->hasRole('vendor') || 
            null === $company || 
            false === $company->belongsToUser($user->id)
        ) {
            return abort(404);
        }

        $resource = compact('product', 'company');
        $deleteProduct = function () use ($resource) {
            $resource['product']->delete();

            return redirect()
                ->route('companyProductHome', $resource['company']->slug)
                ->with('flashSuccess', 'Your item listing was successfully removed.');
        };

        return match($request->input('choice')) {
            '0' => redirect()
                ->route('productShow', $product->id)
                ->with('flashSuccess', 'Your item listing has not been removed.'),
            '1' => $deleteProduct(),
            default => redirect()
                ->back()
                ->with(
                    'flashDanger', 
                    'Oops, something went wrong. Contact system administrator.'
                ),
        };
    }
}
