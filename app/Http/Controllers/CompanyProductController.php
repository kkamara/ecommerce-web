<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Models\Product\Product;
use App\Models\User;

class CompanyProductController extends Controller
{
    /** @property Company */
    protected $company;

    /** @property Product */
    protected $product;

    /** @property User */
    protected $user;

    /**
     * @construct
     */
    public function __construct(
    ) {
        $this->company = new Company;
        $this->product = new Product;
        $this->user    = new User;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  String                    $slug
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index($slug, Request $request)
    {
        $this->company = $this->company->where('slug', $slug)->first();
        $this->user = auth()->user();

        if(
            null === $this->user->hasRole('vendor') || 
            null === $this->company || 
            false === $this->company->belongsToUser($this->user->id)
        ) {
            return abort(404);
        }

        $this->product = $this->product->getProducts($request)
            ->getCompanyProducts($this->company->id)
            ->paginate(7)
            ->appends(request()
            ->except('page'));

        return view('company_product.index', [
            'title' => 'My Products',
            'companyProducts' => $this->product,
            'input' => $request->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  String  $slug
     * @return \Illuminate\Http\Response
     */
    public function create($slug)
    {
        $this->company = $this->company->where('slug', $slug)->first();
        $this->user = auth()->user();

        if(
            null === $this->user->hasRole('vendor') || 
            null === $this->company || 
            false === $this->company->belongsToUser($this->user->id)
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
     * @param  String  $slug
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($slug, Request $request)
    {
        $this->company = $this->company->where('slug', $slug)->first();
        $this->user = auth()->user();

        if(
            false === $this->user->hasRole('vendor') || 
            null === $this->company || 
            false === $this->company->belongsToUser($this->user->id)
        ) {
            return abort(404);
        }

        $this->product = new Product;
        /** @var Array $errors */
        $errors = $this->product->getErrors($request);

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
            'user_id'           => $this->user->id,
            'company_id'        => $this->company->id,
            'name'              => filter_var($request->input('name'), FILTER_SANITIZE_STRING),
            'cost'              => number_format(filter_var($request->input('cost'), FILTER_SANITIZE_STRING), 2),
            'shippable'         => (bool) filter_var($request->input('shippable'), FILTER_SANITIZE_NUMBER_INT),
            'free_delivery'     => (bool) filter_var($request->input('free_delivery'), FILTER_SANITIZE_NUMBER_INT),
            'short_description' => filter_var($request->input('short_description', FILTER_SANITIZE_STRING)),
            'long_description'  => filter_var($request->input('long_description', FILTER_SANITIZE_STRING)),
            'product_details'   => filter_var($request->input('product_details'), FILTER_SANITIZE_STRING),
            'image_path'        => $this->product->uploadImage($request),
        );
        $this->product = $this->product->create($data);

        return redirect()
            ->route('productShow', $this->product->id)
            ->with('flashSuccess', 'Product has been added to your listings.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  String                      $slug
     * @param  \App\Models\Product\Product $product
     * @param  \Illuminate\Http\Request    $request
     * @return \Illuminate\Http\Response
     */
    public function edit($slug, Product $product, Request $request)
    {
        $this->company = $this->company->where('slug', $slug)->first();
        $this->user = auth()->user();

        if(
            false === $this->user->hasRole('vendor') || 
            null === $this->company || 
            false === $this->company->belongsToUser($this->user->id)
        ) {
            return abort(404);
        }

        return view('company_product.edit', [
            'title' => 'Edit '.$this->product->name,
        ])->with(compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  String                      $slug
     * @param  \App\Models\Product\Product $product
     * @param  \Illuminate\Http\Request    $request
     * @return \Illuminate\Http\Response
     */
    public function update($slug, Product $product, Request $request)
    {
        $this->product = $product;
        $this->company = $this->company->where('slug', $slug)->first();
        $this->user = auth()->user();

        if(
            false === $this->user->hasRole('vendor') || 
            null === $this->company ||
            false === $this->company->belongsToUser($this->user->id)
        ) {
            return abort(404);
        }

        /** @var Array $errors */
        $errors = $this->product->getErrors($request);
        if(0 < sizeof($errors))
        {
            return redirect()->back()->with([
                'product' => $this->product,
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
        );
        if (false !== $request->hasFile('image')) {
            $data['image_path'] = $this->product->uploadImage($request);
        }
        $this->product->update($data);

        return redirect()
            ->route('productShow', $this->product->id)
            ->with('flashSuccess', 'Product details have been updated.');
    }

    /**
     * Render the company product delete page.
     *
     * @param  String                      $slug
     * @param  \App\Models\Product\Product $product
     * @param  \Illuminate\Http\Request    $request
     * @return \Illuminate\Http\Response
     */
    public function delete($slug, Product $product, Request $request)
    {
        $this->product = $product;
        $this->company = $this->company->where('slug', $slug)->first();
        $this->user = auth()->user();

        if(
            false === $this->user->hasRole('vendor') || 
            null === $this->company || 
            false === $this->company->belongsToUser($this->user->id)
        ) {
            return abort(404);
        }

        return view('company_product.delete', [
            'title' => 'Delete '.$this->product->name,
        ])->with(compact('product'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  String                      $slug
     * @param  \App\Models\Product\Product $product
     * @param  \Illuminate\Http\Request    $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug, Product $product, Request $request)
    {
        $this->product = $product;
        $this->company = $this->company->where('slug', $slug)->first();
        $this->user = auth()->user();

        if(
            false === $this->user->hasRole('vendor') || 
            null === $this->company || 
            false === $this->company->belongsToUser($this->user->id)
        ) {
            return abort(404);
        }

        switch($request->input('choice')) {
            case '0':
                return redirect()
                    ->route('productShow', $this->product->id)
                    ->with('flashSuccess', 'Your item listing has not been removed.');
            case '1':
                $this->product->delete();

                return redirect()
                    ->route('companyProductHome', $this->company->slug)
                    ->with('flashSuccess', 'Your item listing was successfully removed.');
            default:
                return redirect()
                    ->back()
                    ->with(
                        'flashDanger', 
                        'Oops, something went wrong. Contact system administrator.'
                    );
        };
    }
}
