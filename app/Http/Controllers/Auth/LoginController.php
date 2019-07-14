<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Helpers\SessionCart;
use Illuminate\Http\Request;
use Validator;
use JWTAuth;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Authenticate user 
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $message = "Unsuccessful Login";

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json([
                "errors" => $validator->errors(),
                "message" => $message,
            ], config("app.http.bad_request"));
        }

        $credentials = $request->only('email', 'password');

        try 
        {
            if (! $token = JWTAuth::attempt($credentials)) 
            {
                return response()->json([
                    'error' => 'invalid_credentials',
                    "message" => $message,
                ], 400);
            }
        } 
        catch (JWTException $e) 
        {
            return response()->json([
                'error' => 'could_not_create_token',
                "message" => $message,
            ], 500);
        }

        $user = \App\User::where("email", $credentials["email"])->first()->getAllData();

        $message = "Successful Login";
        return response()->json(compact('token', "message", "user"));
    }
}
