<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\SanitiseRequest;
use App\VendorApplication;
use App\UsersAddress;
use App\User;

class VendorApplicationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SanitiseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SanitiseRequest $request)
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
                ], Response::HTTP_CREATED);
            }
            else
            {
                return response()->json([
                    "error" => $applicationError,
                    "message" => "Bad Request"
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            return response()->json([
                "error" => ["Only a guest user can access this resource."],
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED);
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
            $response = true;
        }
        else
        {
            $response = false;
        }

        return response()->json([
            "data" => $response,
            "message" => "Successful"
        ], Response::HTTP_CREATED);
    }
}
