<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Validator;
use App\User;
use JWTAuth;


class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try 
        {
            if (! $token = JWTAuth::attempt($credentials)) 
            {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } 
        catch (JWTException $e) 
        {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function getAuthenticatedUser()
    {
        try 
        {
            if (! $user = JWTAuth::parseToken()->authenticate()) 
            {
                return response()->json(['user_not_found'], 404);
            }
        } 
        catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) 
        {
            return response()->json(['token_expired'], $e->getStatusCode());
        } 
        catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) 
        {
            return response()->json(['token_invalid'], $e->getStatusCode());

        } 
        catch (Tymon\JWTAuth\Exceptions\JWTException $e) 
        {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json(compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    public function update($slug, Request $request)
    {
        $user = auth()->user();

        $requestUser = User::where('slug', '=', $slug)->first();

        if(isset($requestUser->id) && $user->id === $requestUser->id)
        {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|regex:/^[\pL\s\-]+$/u|max:191',
                'last_name' => 'required|string|regex:/^[\pL\s\-]+$/u|max:191',
                'email' => 'required|string|email|max:101',
                'old_password' => 'required',
                'new_password' => 'nullable|confirmed|min:6',
            ]);

            if(empty($validator->errors()->all()))
            {
                if(! app('hash')->check($data['password'], $requestUser->password))
                {
                    if($request->input('new_password') !== NULL)
                    {
                        $data = array();
                        $data['password'] = bcrypt($request->input('new_password'));

                        $requestUser->update($data);
                    }

                    $data = array();
                    $data['first_name'] = filter_var($request->input('first_name'), FILTER_SANITIZE_STRING);
                    $data['last_name']  = filter_var($request->input('last_name'), FILTER_SANITIZE_STRING);
                    $data['email']      = filter_var($request->input('email'), FILTER_SANITIZE_STRING);

                    $slug = User::generateUniqueSlug($data['first_name'] . ' ' . $data['last_name']);
                    $data['slug'] = str_slug($slug, '-');

                    $requestUser->update($data);

                    return redirect()->back()->with('flashSuccess', 'Your settings have been successfully updated.');
                }
                else
                {
                    return redirect()->back()->with('errors', ['Password is incorrect.']);
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
