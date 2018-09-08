<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\Product;

class CompanyProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
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
     * @param  int  $id
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
