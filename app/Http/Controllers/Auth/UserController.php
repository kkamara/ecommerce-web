<?php

namespace App\Http\Controllers\Auth;

use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\SanitiseRequest;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Validator;
use App\User;
use JWTAuth;

class UserController extends Controller
{
    public function authenticate()
    {
        $message = "Unsuccessful";
        try
        {
            if (! $user = JWTAuth::parseToken()->authenticate())
            {
                $error = 'User not found';
                $response = compact("error", "message");
                return response()->json($response, Response::HTTP_NOT_FOUND);
            }
        }
        catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e)
        {
            $error = 'Token expired';
            $response = compact("error", "message");
            return response()->json($response, $e->getStatusCode());
        }
        catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e)
        {
            $error = 'Invalid token';
            $response = compact("error", "message");
            return response()->json($response, $e->getStatusCode());

        }
        catch (\Tymon\JWTAuth\Exceptions\JWTException $e)
        {
            $error = 'Missing token';
            $response = compact("error", "message");
            return response()->json($response, $e->getStatusCode());
        }

        $message = "Successful";

        $cart = $user->getDbCart();

        return response()->json(
            array_merge(
                ["data" => new UserResource($user)],
                compact("message", "cart")
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SanitiseRequest  $request
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    public function update($slug, SanitiseRequest $request)
    {
        $requestUser = User::where('slug', '=', $slug)->first();

        if(
            (!$user = User::attemptAuth()) || 
            !$requestUser->id || 
            $user->id !== $requestUser->id
        ) {
            return response()->json([
                "message" => "Unauthorized"
            ], Response::HTTP_UNAUTHORIZED);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|regex:/^[\pL\s\-]+$/u|max:191',
            'last_name' => 'required|string|regex:/^[\pL\s\-]+$/u|max:191',
            'email' => 'required|string|email|max:101',
            'old_password' => 'required',
            'new_password' => 'nullable|confirmed|min:6',
        ]);

        if(!empty($validator->errors()->all()))
        {
            return response()->json([
                'error' => $validator->errors()->all(),
                "message" => "Bad Request"
            ], Response::HTTP_BAD_REQUEST);
        }

        if(! app('hash')->check($data['password'], $requestUser->password))
        {
            return response()->json([
                'error' => 'Password is incorrect.',
                "message" => "Bad Request"
            ], Response::HTTP_BAD_REQUEST);
        }

        if($request->input('new_password') !== NULL)
        {
            $requestUser->update(['password' => bcrypt($request->input('new_password'))]);
        }

        $newUserData = array();
        $newUserData['first_name'] = filter_var($request->input('first_name'), FILTER_SANITIZE_STRING);
        $newUserData['last_name']  = filter_var($request->input('last_name'), FILTER_SANITIZE_STRING);
        $newUserData['email']      = filter_var($request->input('email'), FILTER_SANITIZE_STRING);

        $slug = User::generateUniqueSlug($newUserData['first_name'] . ' ' . $newUserData['last_name']);
        $newUserData['slug'] = str_slug($slug, '-');

        $requestUser->update($newUserData);

        return response()->json([
            "data" => new UserResource($user),
            "message"=>"Successful",
        ]);
    }
}
