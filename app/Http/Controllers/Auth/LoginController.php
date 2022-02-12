<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\RedisCartHelper;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @param RedisCartHelper $redisClient
     * @param ?User             $user
     * @return void
     */
    public function __construct(
        protected RedisCartHelper $redisClient = new RedisCartHelper, 
        protected ?User $user = new User,
    ) {
        $this->middleware('guest')->except('delete');
    }

    /**
     * Renders login page.
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('login.create', array(
            'title' => 'Login',
            'fromOrder' => request('fromOrder'),
            'loginEmails' => array_values(User::getTestUsers()),
        ));
    }

    /**
     * Performs login attempt.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if(false === empty($validator->errors()->all()))
        {
            return view('login.create', array(
                'title' => 'Login',
                'input' => $request->input(),
                'errors' => $validator->errors()->all(),
                'logins' => array_values(User::getTestUsers()),
            ));
        }

        if(
            false === Auth::attempt(array(
                'email' => filter_var(request('email'), FILTER_SANITIZE_EMAIL),
                'password' => request('password'),
            ))
        ) {
            return view('login.create', array(
                'title' => 'Login',
                'input' => $request->input(),
                'errors' => array('Invalid login credentials provided'),
                'logins' => array_values(User::getTestUsers()),
            ));
        }

        /**
         * @var Array
         */
        $sessionCart = $this->redisClient->getSessionCart();

        /**
        * login
        * if login then redirect to checkout page if was prompted to login/register
        * if normal login then redirect to home
        * redirect back if false
        */
        if (0 < count($sessionCart)) {
            /** @var User */
            $user = auth()->user();
            $user->moveSessionCartToDbCart($sessionCart);
            return to_route('orderCreate');
        } else {
            return to_route('home');
        }
    }

    public function delete()
    {
        Auth::logout();

        return to_route('home');
    }
}
