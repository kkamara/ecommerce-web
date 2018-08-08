<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VendorApplication;
use App\User;

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

        if(! $user->hasRole('vendor'))
        {
            return view('vendor.create', [
                'title' => 'Become a vendor',
            ]);
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

        if(! $user->hasRole('vendor'))
        {
            if(! VendorApplication::hasUserApplied($user->id))
            {
                if(! VendorApplication::hasApplicationBeenRejected($user->id))
                {
                    VendorApplication::create([
                        'user_id' => $user->id,
                    ]);

                    return redirect()->route('vendorShow');
                }
                else
                {
                    return redirect()->back()->with('flashDanger', 'Unfortunately your previous application was rejected and you cannot apply again. For more information contact administrator.');
                }
            }
            else
            {
                return redirect()->back()->with('flashDanger', 'Your existing application is being processed.');
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
