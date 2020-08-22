<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use App\UsersAddress;
use Validator;

class UsersAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \App\User::attemptAuth();
        $usersAddresses = UsersAddress::where('user_id', $user->id)->paginate(10);

        return response()->json(compact('usersAddresses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = \App\User::attemptAuth();

        $validator = Validator::make($request->all(), [
            'building_name' => 'required|string|max:191',
            'street_address1' => 'required|max:191',
            'street_address2' => 'max:191',
            'street_address3' => 'max:191',
            'street_address4' => 'max:191',
            'postcode' => 'required|string|min: 5|max:191',
            'city' => 'required|string|min: 4|max:191',
            'country' => 'required|string|min: 4|max:191',
            'phone_number_extension' => 'required|min: 2|max:191',
            'phone_number' => 'required|min: 5|max:191',
            'mobile_number_extension' => 'max:191',
            'mobile_number' => 'max:191',
        ]);

        if(empty($validator->errors()->all()))
        {
            if(in_array(request('country'), CommonHelper::getCountriesList()))
            {
                $data = array(
                    'user_id' => $user->id,

                    'building_name' => request('building_name'),
                    'street_address1' => request('street_address1'),
                    'street_address2' => request('street_address2'),
                    'street_address3' => request('street_address3'),
                    'street_address4' => request('street_address4'),
                    'postcode' => request('postcode'),
                    'city' => request('city'),
                    'country' => request('country'),
                    'county' => request('county'), // nullable
                    'phone_number_extension' => request('phone_number_extension'),
                    'phone_number' => request('phone_number'),
                    'mobile_number_extension' => request('mobile_number_extension'), // nullable
                    'mobile_number' => request('mobile_number'), // nullable
                );

                UsersAddress::create($data);

                return response()->json([
                    "message" => "Successful"
                ], Response::HTTP_CREATED);
            }
            else
            {
                return response()->json([
                    'errors' => ['Invalid country provided'],
                    "message" => "Successful",
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            return response()->json([
                'errors' => $validator->errors()->all(),
                "message" => "Successful",
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsersAddress $usersAddress, Request $request)
    {
        $user = \App\User::attemptAuth();
        if($usersAddress['user_id'] === $user->id)
        {
            $validator = Validator::make($request->all(), [
                'building_name' => 'required|string|max:191',
                'street_address1' => 'required|max:191',
                'street_address2' => 'max:191',
                'street_address3' => 'max:191',
                'street_address4' => 'max:191',
                'postcode' => 'required|string|min: 5|max:191',
                'city' => 'required|string|min: 4|max:191',
                'country' => 'required|string|min: 4|max:191',
                'phone_number_extension' => 'required|min: 2|max:191',
                'phone_number' => 'required|min: 5|max:191',
                'mobile_number_extension' => 'max:191',
                'mobile_number' => 'max:191',
            ]);

            if(empty($validator->errors()->all()))
            {
                if(in_array(request('country'), CommonHelper::getCountriesList()))
                {
                    $data = array(
                        'building_name' => request('building_name'),
                        'street_address1' => request('street_address1'),
                        'street_address2' => request('street_address2'),
                        'street_address3' => request('street_address3'),
                        'street_address4' => request('street_address4'),
                        'postcode' => request('postcode'),
                        'city' => request('city'),
                        'country' => request('country'),
                        'county' => request('county'), // nullable
                        'phone_number_extension' => request('phone_number_extension'),
                        'phone_number' => request('phone_number'),
                        'mobile_number_extension' => request('mobile_number_extension'), // nullable
                        'mobile_number' => request('mobile_number'), // nullable
                    );

                    UsersAddress::where('id', $usersAddress->id)->update($data);

                    return response()->json(["message" => "Successful"]);
                }
                else
                {
                    return response()->json([
                        'errors' => ['Invalid country provided'],
                        "message" => "Successful",
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
            else
            {
                return response()->json([
                    'errors' => $validator->errors()->all(),
                    "message" => "Successful",
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            return response()->json([
                "message" => "Unauthorized",
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UsersAddress $usersAddress, Request $request)
    {     
        $user = \App\User::attemptAuth();
        if($usersAddress['user_id'] === $user->id)
        {
            $validator = Validator::make($request->all(), [
                'choice' => 'required|boolean',
            ]);

            $user = auth()->user();

            if(empty($validator->errors()->all()))
            {
                $choice = (bool) $request->input('choice');

                if($choice !== FALSE)
                {
                    UsersAddress::destroy($usersAddress->id);
                }

                return response()->json(["message" => "Successful"]);
            }
            else
            {
                return response()->json([
                    "errors" => $validator->errors()->all(),
                    "message" => "Unsuccessful",
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            return response()->json([
                "message" => "Unauthorized",
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
