<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VendorApplication;
use App\UsersAddress;
use App\User;

class VendorApplicationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = \App\User::attemptAuth();

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

                return response()->json([
                    "message" => "Successful"
                ], config("app.http.created"));
            }
            else
            {
                return response()->json([
                    "errors" => $applicationError,
                    "message" => "Unsuccessful"
                ], config("app.http.bad_request"));
            }
        }
        else
        {
            return response()->json([
                "errors" => ["Only a guest user can access this resource."],
                "message" => "Unauthorized"
            ], config("app.http.unauthorized"));
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
        $user = \App\User::attemptAuth();

        if(VendorApplication::hasUserApplied($user->id))
        {
            $response = "Yes";
        }
        else
        {
            $response = "No";
        }

        return response()->json([
            "data" => $response,
            "message" => "Successful"
        ], config("app.http.created"));
    }
}
