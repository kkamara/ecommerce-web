<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Helpers\CommonHelper;
use App\Http\Requests\SanitiseRequest;
use App\UserPaymentConfig;
use Validator;

class UserPaymentConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \App\User::attemptAuth();

        $billingCards = UserPaymentConfig::
            where('user_id', $user->id)
            ->paginate(10);

        $message = "Successful";
        return response()->json(array_merge([
            ["data" => $billingCards], 
            compact('message'),
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SanitiseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserPaymentConfig $userPaymentConfig, SanitiseRequest $request)
    {
        $user = \App\User::attemptAuth();

        $validator = Validator::make($request->all(), [
            'card_holder_name' => 'required|min: 6|max: 191',
            'card_number' => 'required|digits: 16',
            'expiry_date' => 'required', // format 2018-01

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
            $expiry_date = explode('-', $request->input('expiry_date'));
            $expiry_year = $expiry_date[0];
            $expiry_month = $expiry_date[1];

            if(strtotime(date("$expiry_year-$expiry_month")) >= strtotime(date('Y-m')))
            {
                if(in_array(request('country'), CommonHelper::getCountriesList()))
                {
                    $data = array(
                        'user_id' => $user->id,

                        'card_holder_name' => request('card_holder_name'),
                        'card_number' => request('card_number'),
                        'expiry_month' => $expiry_month,
                        'expiry_year' => $expiry_year,

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

                    UserPaymentConfig::create($data);

                    return response()->json([
                        "message" => "Successful"
                    ], Response::HTTP_CREATED);
                }
                else
                {
                    return response()->json([
                        'error' => 'Invalid country provided',
                        "message" => "Bad Request"
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
            else
            {
                return response()->json([
                    'error' => 'Invalid expiry date provided.',
                    "message" => "Bad Request"
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            return response()->json([
                'error' => $validator->errors()->all(),
                "message" => "Bad Request"
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SanitiseRequest  $request
     * @param  \App\UserPaymentConfig  $userPaymentConfig
     * @return \Illuminate\Http\Response
     */
    public function update(SanitiseRequest $request, UserPaymentConfig $userPaymentConfig)
    {
        $user = \App\User::attemptAuth();
        if($userPaymentConfig['user_id'] === $user->id)
        {
            $validator = Validator::make($request->all(), [
                'card_holder_name' => 'required|min: 6|max: 191',
                'card_number' => 'required|digits: 16',
                'expiry_date' => 'required', // format 2018-01

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
                $expiry_date = explode('-', $request->input('expiry_date'));
                $expiry_year = $expiry_date[0];
                $expiry_month = $expiry_date[1];

                if(strtotime(date("$expiry_year-$expiry_month")) >= strtotime(date('Y-m')))
                {
                    if(in_array(request('country'), CommonHelper::getCountriesList()))
                    {
                        $data = array(
                            'card_holder_name' => request('card_holder_name'),
                            'card_number' => request('card_number'),
                            'expiry_month' => $expiry_month,
                            'expiry_year' => $expiry_year,

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

                        UserPaymentConfig::where('id', $userPaymentConfig->id)->update($data);

                        return response()->json(["message" => "Successful"]);
                    }
                    else
                    {
                        return response()->json([
                            'error' => 'Invalid country provided',
                            "message" => "Bad Request"
                        ], Response::HTTP_BAD_REQUEST);
                    }
                }
                else
                {
                    return response()->json([
                        'error' => 'Invalid expiry date provided.',
                        "message" => "Bad Request"
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
            else
            {
                return response()->json([
                    'error' => $validator->errors()->all(),
                    "message" => "Bad Request"
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            return response()->json([
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserPaymentConfig  $userPaymentConfig
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserPaymentConfig $userPaymentConfig, SanitiseRequest $request)
    {
        $user = \App\User::attemptAuth();
        if($userPaymentConfig['user_id'] === $user->id)
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
                    UserPaymentConfig::destroy($userPaymentConfig->id);

                    return response()->json(["message" => "Successful"]);
                }
                else
                {
                    return response()->json([
                        'error' => 'Billing card has not been deleted.',
                        "message" => "Bad Request"
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
            else
            {
                return response()->json([
                    'error', $validator->errors()->all(),
                    "message" => "Bad Request"
                ], Response::HTTP_BAD_REQUEST);
            }
        }
        else
        {
            return response()->json([
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}

