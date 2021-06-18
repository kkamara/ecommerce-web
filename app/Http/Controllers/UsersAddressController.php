<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User\UsersAddress;
use App\Models\User;

class UsersAddressController extends Controller
{
    /** @property User */
    protected $user;

    /** @property UsersAddress */
    protected $usersAddress;

    public function __construct() {
        $this->user         = new User;
        $this->usersAddress = new UsersAddress;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->user = auth()->user();
        $this->usersAddress = $this->usersAddress
            ->where('user_id', $this->user->id)
            ->paginate(10);

        return view('users_address.index', [
            'title' => 'Addresses',
            'usersAddresses' => $this->usersAddress,
        ]);
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
        $this->user = auth()->user();

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

        if(false === empty($validator->errors()->all())) {
            return redirect()->back()->with([
                'errors' => $validator->errors()->all(),
            ]);
        }

        if(false === in_array(request('country'), getCountriesList())) {
            return redirect()->back()->with([
                'errors' => ['Invalid country provided'],
            ]);
        }

        $data = array(
            'user_id' => $this->user->id,
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
        $this->usersAddress->create($data);

        return redirect()
            ->route('addressHome')
            ->with(
                'flashSuccess', 
                'Address successfully created.'
            );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UsersAddress $usersAddress)
    {
        $this->usersAddress = $usersAddress;
        $this->user = auth()->user();
        
        if($this->usersAddress['user_id'] !== $this->user->id)
        {
            return abort(404);
        }

        return view('users_address.edit', [
            'title' => 'Edit Address',
            'usersAddress' => $this->usersAddress,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\User\UsersAddress $usersAddress
     * @param  \Illuminate\Http\Request      $request
     * @return \Illuminate\Http\Response
     */
    public function update(UsersAddress $usersAddress, Request $request)
    {
        $this->usersAddress = $usersAddress;
        $this->user = auth()->user();

        if($this->usersAddress['user_id'] !== $this->user->id)
        {
            return abort(404);
        }

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

        if(false === empty($validator->errors()->all()))
        {
            return redirect()->back()->with([
                'errors' => $validator->errors()->all(),
            ]);
        }
        
        if(false === in_array(request('country'), getCountriesList()))
        {
            return redirect()->back()->with([
                'errors' => ['Invalid country provided'],
            ]);
        }

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
        $this->usersAddress
            ->where('id', $usersAddress->id)
            ->update($data);

        return redirect()
            ->route('addressHome')
            ->with(
                'flashSuccess', 
                'Address successfully updated.'
            );
    }

    /**
     * Render the user address delete page.
     *
     * @param  \App\Models\User\UsersAddress $usersAddress
     * @return \Illuminate\Http\Response
     */
    public function delete(UsersAddress $usersAddress)
    {
        $this->usersAddress = $usersAddress;
        $this->user = auth()->user();

        if($this->usersAddress['user_id'] !== $this->user->id)
        {
            return abort(404);
        }

        return view('users_address.delete', [
            'title' => 'Delete Address',
            'usersAddress' => $this->usersAddress,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User\UsersAddress $usersAddress
     * @param  \Illuminate\Http\Request      $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(UsersAddress $usersAddress, Request $request)
    {
        $this->usersAddress = $usersAddress;
        $this->user = auth()->user();
        
        if($this->usersAddress['user_id'] !== $this->user->id) {
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

        if($choice !== FALSE) {
            $this->usersAddress->destroy($this->usersAddress->id);

            return redirect()
                ->route('addressHome')
                ->with(
                    'flashSuccess', 
                    'Address has been deleted successfully.'
                );
        } else {
            return redirect()
                ->route('addressHome')
                ->with(
                    'flashSuccess', 
                    'Address has not been deleted.'
                );
        }
    }
}
