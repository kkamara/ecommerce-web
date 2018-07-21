<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
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
     * @return \App\User
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
        $validator = Validate::make($request->all(), [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6|confirmed',

            'building_name' => 'required|string|max:191',
            'street_address1' => 'required|max:191',
            'street_address2' => 'max:191',
            'street_address3' => 'max:191',
            'street_address4' => 'max:191',
            'postcode' => 'required|string|min: 5|max:191',
            'city' => 'required|string|min: 4|max:191',
            'country' => 'required|string|min: 4|max:191',
            'phone_number_ext' => 'required|min: 2|max:191',
            'phone_number' => 'required|min: 5|max:191',
            'mobile_number_ext' => 'max:191',
            'mobile_number' => 'max:191',

            'card_holder_name' => 'required|min: 6|max: 191',
            'card_number' => 'required|digits: 16',
            'expiry_date' => 'required', // format 2018-01
        ]);

        if(empty($validator->errors()->all()))
        {
            $expiry_date = explode('-', request('expiry_date'));
            $expiry_year = $expiry_date[0];
            $expiry_month = $expiry_date[1];

            if(strtotime(date("$expiry_year-$expiry_month")) >= strtotime(date('Y-m')))
            {

                $data = array(
                    'user' => array(
                        'slug' => str_slug(request('first_name') . ' ' . request('last_name'), '-'),
                        'first_name' => request('first_name'),
                        'last_name' => request('last_name'),
                        'email' => request('email'),
                        'password' => bcrypt('password'),
                    ),
                    'user_address' => array(
                        'building_name' => request('building_name'),
                        'street_address1' => request('street_address1'),
                        'street_address2' => request('street_address2'),
                        'street_address3' => request('street_address3'),
                        'street_address4' => request('street_address4'),
                        'postcode' => request('postcode'),
                        'city' => request('city'),
                        'country' => request('country'),
                        'county' => request('county'), // nullable
                        'phone_number_extension' => request('phone_number_ext'),
                        'phone_number' => request('phone_number'),
                        'mobile_number_extension' => request('mobile_number_ext'), // nullable
                        'mobile_number' => request('mobile_number'), // nullable
                    ),
                    'user_payment_config' => array(
                        'card_holder_name' => request('card_holder_name'),
                        'card_number' => request('card_number'),
                        'expiry_month' => $expiry_month,
                        'expiry_year' => $expiry_year,
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
                $cacheCart = getCacheCart();
                if(!empty($cacheCart))
                {
                    $user->moveCacheCartToDbCart($cacheCart);
                }

                Auth::attempt([
                    'email' => $user->email,
                    'password' => request('password')
                ], 1);

                return redirect()->route('home');
            }
            else
            {
                return redirect()->back()->with('errors', [
                    'Invalid expiry date provided.'
                ]);
            }
        }
        else
        {
            return redirect()->back()->with('errors', $validator->errors()->all());
        }
    }
}
