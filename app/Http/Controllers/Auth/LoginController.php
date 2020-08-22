<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Helpers\CacheCart;
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

        $client_hash_key = $request->get("client_hash_key");

        if ($client_hash_key === null) {
            return response()->json([
                "message" => "Client hash key not given"
            ], 409);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json([
                "errors" => $validator->errors(),
                "message" => $message,
            ], Response::HTTP_BAD_REQUEST);
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

        $user = \App\User::where("email", $credentials["email"])->first();
        $responseData = $user->getAllData();

        // add to cart if cache cart not empty
        $cacheCart = CacheCart::getCacheCart($client_hash_key);
        if(!empty($cacheCart))
        {
            $user->moveCacheCartToDbCart($cacheCart, $client_hash_key);
        }

        $cart = $user->getDbCart();

        $message = "Successful Login";
        return response()->json(
            array_merge(
                ["user" => $responseData],
                compact("message", "token", "cart")
            )
        );
    }
}
