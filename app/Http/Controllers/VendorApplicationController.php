<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VendorApplication;
use App\UsersAddress;

class VendorApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        $usersAddresses = UsersAddress::where('user_id', $user->id)->get();

        if($user->hasNoRole())
        {
            return view('vendor.create', [
                'title' => 'Become a vendor',
            ])->with(compact('usersAddresses'));
        }
        else
        {
            return redirect()->route('home');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $companyName = filter_var($request->input('company_name'), FILTER_SANITIZE_STRING);
        $usersAddressId = filter_var($request->input('users_address'), FILTER_SANITIZE_NUMBER_INT);

        if($user->hasNoRole())
        {
            if(! $applicationError = VendorApplication::getError($user->id, $companyName, $usersAddressId))
            {
                VendorApplication::create([
                    'user_id' => $user->id,
                    'proposed_company_name' => $companyName,
                    'users_addresses_id' => $usersAddressId,
                ]);

                return redirect()->route('vendorShow');
            }
            else
            {
                return redirect()->back()->with('flashDanger', $applicationError);
            }
        }
        else
        {
            return redirect()->route('home');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = auth()->user();

        if(VendorApplication::hasUserApplied($user->id))
        {
            return view('vendor.show', [
                'title' => 'Application Sent',
            ]);
        }
        else
        {
            return redirect()->route('home');
        }
    }
}
