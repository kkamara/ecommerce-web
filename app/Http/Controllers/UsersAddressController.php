<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UsersAddress;
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
        $user = auth()->user();
        if($usersAddress['user_id'] === $user->id)
        {
            //
        }
        else
        {
            return abort(404);
        }
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
        if($usersAddress['user_id'] === $user->id)
        {
            //
        }
        else
        {
            return abort(404);
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
            //
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
            //
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
