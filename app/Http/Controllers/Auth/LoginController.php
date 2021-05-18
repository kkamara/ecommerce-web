<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\SessionCart;
use Illuminate\Http\Request;
use Validator;
use Auth;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /** @var array to store seeder logins for site users */
    protected $logins = array(
        'mod' => array(
            'email' => 'mod@mail.com',
            'pass'  => 'secret',
        ),
        'vendor' => array(
            'email' => 'vendor@mail.com',
            'pass'  => 'secret',
        ),
        'guest' => array(
            'email' => 'guest@mail.com',
            'pass'  => 'secret',
        ),
    );

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('delete');
    }

    public function create()
    {
        return view('login.create', array(
            'title' => 'Login',
            'fromOrder' => request('fromOrder'),
            'logins' => $this->logins,
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if(empty($validator->errors()->all()))
        {
            $email = filter_var(request('email'), FILTER_SANITIZE_EMAIL);

            $creds = array(
                'email' => request('email'),
                'password' => request('password'),
            );

            if(Auth::attempt($creds))
            {
                $user = auth()->user();
                $sessionCart = SessionCart::getSessionCart();

                /**
                * login
                * if login then redirect to checkout page if was prompted to login/register
                * if normal login then redirect to home
                * redirect back if false
                */

                if(! empty($sessionCart))
                {
                    $user->moveSessionCartToDbCart($sessionCart);

                    return redirect()->route('orderCreate');
                }
                else
                {
                    return redirect()->route('home');
                }
            }
            else
            {
                return view('login.create', array(
                    'title' => 'Login',
                    'input' => $request->input(),
                    'errors' => array('Invalid login credentials provided'),
                    'logins' => $this->logins,
                ));
            }
        }
        else
        {
            return view('login.create', array(
                'title' => 'Login',
                'input' => $request->input(),
                'errors' => $validator->errors()->all(),
                'logins' => $this->logins,
            ));
        }
    }

    public function edit() {}
    public function update() {}

    public function delete()
    {
        Auth::logout();

        return redirect()->route('home');
    }
}
