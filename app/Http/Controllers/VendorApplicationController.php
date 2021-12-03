<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company\VendorApplication;
use App\Models\User\UsersAddress;
use App\Models\User;

class VendorApplicationController extends Controller
{
    public function __construct(
        protected ?User $user,
        protected ?UsersAddress $usersAddress,
        protected ?VendorApplication $vendorApplication,
    ) {
        $this->user              = new User;
        $this->usersAddress      = new UsersAddress;
        $this->vendorApplication = new VendorApplication;
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $usersAddresses = $this->usersAddress
            ->where('user_id', auth()->user()->id)
            ->get();

        if($this->user->hasNoRole()) {
            return view('vendor.create', [
                'title' => 'Become a vendor',
                'usersAddresses' => $usersAddresses,
            ]);
        }
        
        return redirect()->route('home');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $companyName = filter_var($request->input('company_name'), FILTER_SANITIZE_STRING);
        $usersAddressId = filter_var($request->input('users_address'), FILTER_SANITIZE_NUMBER_INT);

        if(false === $this->user->hasNoRole())
        {
            return redirect()->route('home');
        }

        if(
            $applicationError = $this->vendorApplication->getError(
                $this->user->id, 
                $companyName, 
                $usersAddressId
            )
        ) {
            return redirect()->back()->with('flashDanger', $applicationError);
        }
    
        $this->vendorApplication->create([
            'user_id' => auth()->user()->id,
            'proposed_company_name' => $companyName,
            'users_addresses_id' => $usersAddressId,
        ]);

        return redirect()->route('vendorShow');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if(false === $this->vendorApplication->hasUserApplied(auth()->user()->id)) {
            return redirect()->route('home');
        }
    
        return view('vendor.show', [
            'title' => 'Application Sent',
        ]);
    }
}
