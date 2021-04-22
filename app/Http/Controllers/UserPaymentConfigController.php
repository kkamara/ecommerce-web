<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserPaymentConfig;
use Validator;

class UserPaymentConfigController extends Controller
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
    public function index()
    {
        $user = auth()->user();

        $billingCards = UserPaymentConfig::where('user_id', $user->id)->paginate(10);

        return view('user_payment_config.index', [
            'title' => 'Billing Cards'
        ])->with(compact('billingCards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user_payment_config.create', [
            'title' => 'Add Billing Card',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserPaymentConfig $userPaymentConfig, Request $request)
    {
        $user = auth()->user();

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
                if(in_array(request('country'), getCountriesList()))
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

                    return redirect()->route('billingHome')->with('flashSuccess', 'Billing card successfully created.');
                }
                else
                {
                    return redirect()->back()->with([
                        'errors' => ['Invalid country provided'],
                    ]);
                }
            }
            else
            {
                return redirect()->back()->with([
                    'errors' => ['Invalid expiry date provided.'],
                ]);
            }
        }
        else
        {
            return redirect()->back()->with([
                'errors' => $validator->errors()->all(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserPaymentConfig  $userPaymentConfig
     * @return \Illuminate\Http\Response
     */
    public function show(UserPaymentConfig $userPaymentConfig)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserPaymentConfig  $userPaymentConfig
     * @return \Illuminate\Http\Response
     */
    public function edit(UserPaymentConfig $userPaymentConfig)
    {
        $user = auth()->user();
        if($userPaymentConfig['user_id'] === $user->id)
        {
            return view('user_payment_config.edit', [
            'title' => 'Edit Billing Card',
            'billingCard' => $userPaymentConfig,
        ]);
        }
        else
        {
            return abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserPaymentConfig  $userPaymentConfig
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserPaymentConfig $userPaymentConfig)
    {
        $user = auth()->user();
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
                    if(in_array(request('country'), getCountriesList()))
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

                        return redirect()->route('billingHome')->with('flashSuccess', 'Billing card successfully updated.');
                    }
                    else
                    {
                        return redirect()->back()->with([
                            'errors' => ['Invalid country provided'],
                        ]);
                    }
                }
                else
                {
                    return redirect()->back()->with([
                        'errors' => ['Invalid expiry date provided.'],
                    ]);
                }
            }
            else
            {
                return redirect()->back()->with([
                    'errors' => $validator->errors()->all(),
                ]);
            }
        }
        else
        {
            return abort(404);
        }
    }

    public function delete(UserPaymentConfig $userPaymentConfig)
    {
        $user = auth()->user();
        if($userPaymentConfig['user_id'] === $user->id)
        {
            return view('user_payment_config.delete', [
                'title' => 'Delete Billing Card',
                'billingCard' => $userPaymentConfig,
            ]);
        }
        else
        {
            return abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserPaymentConfig  $userPaymentConfig
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserPaymentConfig $userPaymentConfig, Request $request)
    {
        $user = auth()->user();
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

                    return redirect()->route('billingHome')->with('flashSuccess', 'Billing card has been deleted successfully.');
                }
                else
                {
                    return redirect()->route('billingHome')->with('flashSuccess', 'Billing card has not been deleted.');
                }
            }
            else
            {
                return redirect()->back()->with('errors', $validator->errors()->all());
            }
        }
        else
        {
            return abort(404);
        }
    }
}

