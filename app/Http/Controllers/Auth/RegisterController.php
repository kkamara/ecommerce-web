<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use App\Http\Requests\SanitiseRequest;
use App\Helpers\CacheCart;
use Validator as Validate;
use App\UserPaymentConfig;
use App\UsersAddress;
use App\User;
use Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function register(SanitiseRequest $request)
    {
        $registerErrors = User::getRegisterErrors($request);

        $input = $request->input();

        $client_hash_key = $request->header("X-CLIENT-HASH-KEY");

        if ($client_hash_key === null) {
            return response()->json([
                "message" => "Client hash key not given"
            ], 409);
        }

        if(true === $registerErrors['present'])
        {
            return response()->json([
                'error' => $registerErrors,
                "message" => "Unsuccessful"
            ], Response::HTTP_BAD_REQUEST);
        }

        $expiry_date = explode('-', request('expiry_date'));
        $expiry_year = filter_var($expiry_date[0], FILTER_SANITIZE_NUMBER_INT);
        $expiry_month = filter_var($expiry_date[1], FILTER_SANITIZE_NUMBER_INT);

        if(strtotime(date("$expiry_year-$expiry_month")) < strtotime(date('Y-m')))
        {
            return response()->json([
                'error' => 'Invalid expiry date provided.',
                "message" => "Unsuccessful"
            ], Response::HTTP_BAD_REQUEST);
        }

        $firstName = filter_var(request('first_name'), FILTER_SANITIZE_STRING);
        $lastName = filter_var(request('last_name'), FILTER_SANITIZE_STRING);
        $email = filter_var(request('email'), FILTER_SANITIZE_EMAIL);
        $password = bcrypt('password');

        $building_name = filter_var(request('building_name'), FILTER_SANITIZE_STRING);
        $streetAddress1 = filter_var(request('street_address1'), FILTER_SANITIZE_STRING);
        $streetAddress2 = filter_var(request('street_address2'), FILTER_SANITIZE_STRING);
        $streetAddress3 = filter_var(request('street_address3'), FILTER_SANITIZE_STRING);
        $streetAddress4 = filter_var(request('street_address4'), FILTER_SANITIZE_STRING);
        $postcode = filter_var(request('postcode'), FILTER_SANITIZE_STRING);
        $city = filter_var(request('city'), FILTER_SANITIZE_STRING);
        $country = filter_var(request('country'), FILTER_SANITIZE_STRING);
        $county = filter_var(request('county'), FILTER_SANITIZE_STRING);
        $phoneNumberExtension = filter_var(request('phone_number_ext'), FILTER_SANITIZE_STRING);
        $phoneNumber = filter_var(request('phone_number'), FILTER_SANITIZE_STRING);
        $mobileNumberExtension = filter_var(request('mobile_number_ext'), FILTER_SANITIZE_STRING);
        $mobileNumber = filter_var(request('mobile_number'), FILTER_SANITIZE_STRING);

        $cardHolderName = filter_var(request('card_holder_name'), FILTER_SANITIZE_STRING);
        $cardNumber = filter_var(request('card_number'), FILTER_SANITIZE_STRING);

        $slug = str_slug($firstName . ' ' . $lastName, '-');

        if(! User::slugIsUnique($slug))
        {
            $slug = User::generateUniqueSlug($slug);
        }

        $data = array(
            'user' => array(
                'slug' => $slug,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => $password,
            ),
            'user_address' => array(
                'building_name' => $building_name,
                'street_address1' => $streetAddress1,
                'street_address2' => $streetAddress2,
                'street_address3' => $streetAddress3,
                'street_address4' => $streetAddress4,
                'postcode' => $postcode,
                'city' => $city,
                'country' => $country,
                'county' => $county, // nullable
                'phone_number_extension' => $phoneNumberExtension,
                'phone_number' => $phoneNumber,
                'mobile_number_extension' => $mobileNumberExtension, // nullable
                'mobile_number' => $mobileNumber, // nullable
            ),
            'user_payment_config' => array(
                'card_holder_name' => $cardHolderName,
                'card_number' => $cardNumber,
                'expiry_month' => $expiryMonth,
                'expiry_year' => $expiryYear,
            ),
        );
        $data['user_payment_config'] = array_merge($data['user_address'], $data['user_payment_config']);

        // create user
        $user = User::create($data['user']);

        $data['user_address']['user_id'] = $user->id;
        $data['user_payment_config']['user_id'] = $user->id;

        // create user address
        UsersAddress::create($data['user_address']);

        // create user payment config
        UserPaymentConfig::create($data['user_payment_config']);

        // add to cart if cache cart not empty
        $cacheCart = CacheCart::getCacheCart($request->header("X-CLIENT-HASH-KEY"));
        if(!empty($cacheCart))
        {
            $user->moveCacheCartToDbCart($cacheCart, $client_hash_key);
        }

        if($validator->fails())
        {
            $errors = array_merge($validator->errors(), compact("message"));
            return response()->json([
                "error" => $validator->errors()->all(),
            ], 400);
        }

        $token = JWTAuth::fromUser($user);
        $cart = $user->getDbCart();

        $message = "Successful";
        return response()->json(
            array_merge(
                [
                    'data' => [
                        'user' => new UserResource($user),
                        'token' => $token,
                        'cart' => $cart,
                    ],
                ],
                compact("message")
            ),
            201
        );
    }
}
