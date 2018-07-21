<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserPaymentConfig;
use Validator;

class UserPaymentConfigController extends Controller
{
    public function __constructor()
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
        //
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
        //
    }

    public function delete(UserPaymentConfig $userPaymentConfig)
    {
        return view('user_payment_config.delete', [
            'title' => 'Delete Billing Card',
            'billingCard' => $userPaymentConfig,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserPaymentConfig  $userPaymentConfig
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserPaymentConfig $userPaymentConfig, Request $request)
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

                return redirect()->route('billingHome')->with('flashSuccess', 'Billing card has been successfully deleted.');
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
}

