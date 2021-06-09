<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersAddress;
use Validator;

class UsersAddressController extends Controller
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
        $usersAddresses = UsersAddress::where('user_id', $user->id)->paginate(10);

        return view('users_address.index', [
            'title' => 'Addresses',
        ])->with(compact('usersAddresses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users_address.create', [
            'title' => 'Add Address',
        ]);
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
            if(in_array(request('country'), getCountriesList()))
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

                return redirect()->route('addressHome')->with('flashSuccess', 'Address successfully created.');
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
                'errors' => $validator->errors()->all(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UsersAddress $usersAddress)
    {
        $user = auth()->user();
        if($usersAddress['user_id'] === $user->id)
        {
            return view('users_address.edit', [
                'title' => 'Edit Address',
            ])->with(compact('usersAddress'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsersAddress $usersAddress, Request $request)
    {
        $user = auth()->user();
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
                if(in_array(request('country'), getCountriesList()))
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

                    return redirect()->route('addressHome')->with('flashSuccess', 'Address successfully updated.');
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
                    'errors' => $validator->errors()->all(),
                ]);
            }
        }
        else
        {
            return abort(404);
        }
    }

    public function delete(UsersAddress $usersAddress)
    {
        $user = auth()->user();
        if($usersAddress['user_id'] === $user->id)
        {
            return view('users_address.delete', [
                'title' => 'Delete Address',
            ])->with(compact('usersAddress'));
        }
        else
        {
            return abort(404);
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
        $user = auth()->user();
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

                    return redirect()->route('addressHome')->with('flashSuccess', 'Address has been deleted successfully.');
                }
                else
                {
                    return redirect()->route('addressHome')->with('flashSuccess', 'Address has not been deleted.');
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
