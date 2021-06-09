<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Helpers\SessionCart;
use App\Models\UserPaymentConfig;
use App\Models\UsersAddress;
use App\Models\User;
use Auth;

class RegisterController extends Controller
{
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function createUser(Request $request)
    {
        return view('register.create', array(
            'title' => 'Register'
        ));
    }

    public function storeUser(Request $request)
    {
        $registerErrors = User::getRegisterErrors($request);

        $input = $request->input();

        if(true === $registerErrors['present'])
        {
            return redirect()->back()->with(array(
                'errors' => $registerErrors,
                'input' => $input,
            ));
        }

        $expiry_date = explode('-', request('expiry_date'));
        $expiry_year = filter_var($expiry_date[0], FILTER_SANITIZE_NUMBER_INT);
        $expiry_month = filter_var($expiry_date[1], FILTER_SANITIZE_NUMBER_INT);

        if(strtotime(date("$expiry_year-$expiry_month")) < strtotime(date('Y-m')))
        {
            return redirect()->back()->with('errors', [
                'Invalid expiry date provided.'
            ]);
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

        $slug = Str::slug($firstName . ' ' . $lastName, '-');

        if(! User::slugIsUnique($slug))
        {
            $slug = User::generateUniqueSlug($slug);
        }

        $expiryMonth = mt_rand(0, 13);
        if (strlen($expiryMonth) < 10) {
            $expiryMonth = '0'.$expiryMonth;
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
                'expiry_year' => mt_rand(2024, 2030),
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

        // // add to cart if cache cart not empty
        $sessionCart = SessionCart::getSessionCart();
        if(!empty($sessionCart))
        {
            $user->moveSessionCartToDbCart($sessionCart);
        }

        Auth::attempt([
            'email' => $user->email,
            'password' => request('password')
        ], 1);

        return redirect()->route('home');
    }
}
