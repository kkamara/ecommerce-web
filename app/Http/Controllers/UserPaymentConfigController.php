<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User\UserPaymentConfig;
use App\Models\User;

class UserPaymentConfigController extends Controller
{
    /**
     * @param User $user
     * @param UserPaymentConfig $userPaymentConfig
     * @return void
     */
    public function __construct(protected User $user = new User, protected UserPaymentConfig $userPaymentConfig = new UserPaymentConfig) {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user_payment_config.index', [
            'title' => 'Billing Cards', 
            'billingCards' => $this->userPaymentConfig
                ->where('user_id', auth()->user())
                ->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user_payment_config.create', ['title' => 'Add Billing Card']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

        if(
            false === empty($validator->errors()->all())
        ) {
            return redirect()->back()->with([
                'errors' => $validator->errors()->all(),
            ]);
        }

        $expiry_date = explode('-', $request->input('expiry_date'));
        $expiry_year = $expiry_date[0];
        $expiry_month = $expiry_date[1];

        if(
            strtotime(date("$expiry_year-$expiry_month")) < strtotime(date('Y-m'))
        ) {
            return redirect()->back()->with([
                'errors' => ['Invalid expiry date provided.'],
            ]);
        }

        if(
            false === in_array(request('country'), getCountriesList())
        ) {
            return redirect()->back()->with([
                'errors' => ['Invalid country provided'],
            ]);
        }

        $data = array(
            'user_id' => auth()->user()->id,
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
        $this->userPaymentConfig->create($data);

        return redirect()
            ->route('billingHome')
            ->with(
                'flashSuccess', 
                config('flash.user_payment_config.store_success'),
            );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User\UserPaymentConfig  $userPaymentConfig
     * @return \Illuminate\Http\Response
     */
    public function edit(UserPaymentConfig $userPaymentConfig)
    {
        return match($userPaymentConfig['user_id']) {
            auth()->user()->id => view('user_payment_config.edit', [
                'title' => 'Edit Billing Card',
                'billingCard' => $userPaymentConfig,
            ]),
            default => abort(404),
        };
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request            $request
     * @param  \App\Models\User\UserPaymentConfig  $userPaymentConfig
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserPaymentConfig $userPaymentConfig)
    {
        if(
            $userPaymentConfig['user_id'] !== auth()->user()->id
        ) {
            return abort(404);
        }

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

        if(
            false === empty($validator->errors()->all())
        ) {
            return redirect()->back()->with([
                'errors' => $validator->errors()->all(),
            ]);
        }

        $expiry_date = explode('-', $request->input('expiry_date'));
        $expiry_year = $expiry_date[0];
        $expiry_month = $expiry_date[1];

        if(
            strtotime(date("$expiry_year-$expiry_month")) < strtotime(date('Y-m'))
        ) {
            return redirect()->back()->with([
                'errors' => ['Invalid expiry date provided.'],
            ]);
        }

        if(
            false === in_array(request('country'), getCountriesList())
        ) {
            return redirect()->back()->with([
                'errors' => ['Invalid country provided'],
            ]);
        }

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
        $this->userPaymentConfig
            ->where('id', $this->userPaymentConfig->id)
            ->update($data);

        return redirect()
            ->route('billingHome')
            ->with(
                'flashSuccess', 
                config('flash.user_payment_config.update_success'),
            );
    }

    /**
     * Render the payment config delete page.
     *
     * @param  \App\Models\User\UserPaymentConfig $userPaymentConfig
     * @return \Illuminate\Http\Response
     */
    public function delete(UserPaymentConfig $userPaymentConfig)
    {
        if($userPaymentConfig['user_id'] !== auth()->user()->id)
        {
            return abort(404);
        }

        return view('user_payment_config.delete', [
            'title' => 'Delete Billing Card',
            'billingCard' => $userPaymentConfig,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User\UserPaymentConfig  $userPaymentConfig
     * @param  \Illuminate\Http\Request            $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserPaymentConfig $userPaymentConfig, Request $request)
    {
        if(
            $userPaymentConfig['user_id'] !== auth()->user()->id
        ) {
            return abort(404);
        }

        $validator = Validator::make($request->all(), [
            'choice' => 'required|boolean',
        ]);

        if(false === empty($validator->errors()->all()))
        {
            return redirect()->back()->with('errors', $validator->errors()->all());
        }

        $choice = (bool) $request->input('choice');

        switch($choice) {
            case true:
                $userPaymentConfig->delete();

                return redirect()
                    ->route('billingHome')
                    ->with(
                        'flashSuccess', 
                        config('user_payment_config.destroy_y'),
                    );
                break;
            default:
                return redirect()
                    ->route('billingHome')
                    ->with(
                        'flashSuccess', 
                        config('user_payment_config.destroy_n'),
                    );
                break;
        }
    }
}
