<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\SessionCartHelper;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /** @var array to store seeder logins for site users */
    protected $loginEmails = array(
        array(
            'email' => 'mod@mail.com',
            'role'  => 'mod',
        ),
        array(
            'email' => 'vendor@mail.com',
            'role'  => 'vendor',
        ),
        array(
            'email' => 'guest@mail.com',
            'role'  => 'guest',
        ),
    );

    /** @property SessionCartHelper */
    protected $sessionCartHelper;

    /** @property User */
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sessionCartHelper = new SessionCartHelper;
        $this->user              = new User;
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
            'loginEmails' => $this->loginEmails,
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
                'logins' => $this->loginEmails,
            ));
        }

        $sanitizedEmail = filter_var(request('email'), FILTER_SANITIZE_EMAIL);

        $creds = array(
            'email' => $sanitizedEmail,
            'password' => request('password'),
        );

        if(false === Auth::attempt($creds))
        {
            return view('login.create', array(
                'title' => 'Login',
                'input' => $request->input(),
                'errors' => array('Invalid login credentials provided'),
                'logins' => $this->loginEmails,
            ));
        }

        $this->user              = auth()->user();
        $this->sessionCartHelper = $this->sessionCartHelper->getSessionCart();

        /**
        * login
        * if login then redirect to checkout page if was prompted to login/register
        * if normal login then redirect to home
        * redirect back if false
        */
        if (0 < count($this->sessionCartHelper)) {
            $this->user->moveSessionCartToDbCart($this->sessionCartHelper);
            return redirect()->route('orderCreate');
        } else {
            return redirect()->route('home');
        }
    }

    public function delete()
    {
        Auth::logout();

        return redirect()->route('home');
    }
}
