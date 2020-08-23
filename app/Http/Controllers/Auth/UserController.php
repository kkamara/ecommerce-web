<?php

namespace App\Http\Controllers\Auth;

use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use App\Http\Requests\SanitiseRequest;
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
                $response = array_merge(['user_not_found'], compact("message"));
                return response()->json($response, 404);
            }
        }
        catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e)
        {
            $response = array_merge(['token_expired'], compact("message"));
            return response()->json($response, $e->getStatusCode());
        }
        catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e)
        {
            $response = array_merge(['token_invalid'], compact("message"));
            return response()->json($response, $e->getStatusCode());

        }
        catch (\Tymon\JWTAuth\Exceptions\JWTException $e)
        {
            $response = array_merge(['token_absent'], compact("message"));
            return response()->json($response, $e->getStatusCode());
        }

        $message = "Successful";

        $responseData = $user->getAllData();
        $cart = $user->getDbCart();

        return response()->json(
            array_merge(
                ["user" => $responseData],
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
                    $user = $requestUser->getAllData();

                    return response()->json([
                        "message"=>"Successful",
                        "user" => $user,
                    ]);
                }
                else
                {
                    return response()->json([
                        'errors' => ['Password is incorrect.'],
                        "message" => "Unsuccessful"
                    ], Response::HTTP_BAD_REQUEST);
                }
            }
            else
            {
                return response()->json([
                    'errors' => $validator->errors()->all(),
                    "message" => "Unsuccessful"
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
