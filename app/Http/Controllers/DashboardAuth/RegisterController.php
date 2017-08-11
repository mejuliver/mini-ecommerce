<?php

namespace App\Http\Controllers\DashboardAuth;

use App\Dashboard;
use App\profile;
use App\roles_id;
use App\permissions_id;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

//use facade storage
use Illuminate\Support\Facades\Storage;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/app/system/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('dashboard.guest');
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
            'first_name' => 'string|required|max:255|unique:profiles',
            'last_name' => 'string|required|max:255',
            'email' => 'required|email|max:255|unique:profiles',
            'username' => 'required|alpha_dash|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return Dashboard
     */
    protected function create(array $data)
    {
        //create user
        $user = Dashboard::create([
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
            'real_password' => $data['password'],
        ]);
        //create profile
        profile::create([
            'username' => $data['username'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);
        //create roles
        roles_id::create([
            'role_id' => 1,
            'username' => $data['username'],
        ]);

        //create permission
        permissions_id::create([
            'perm_id' => 0,
            'username' => $data['username'],
        ]);

        //create user folder
        Storage::makeDirectory($data['username']);
        //create items folder
        Storage::makeDirectory($data['username'].'/items');
        //create profile folder
        Storage::makeDirectory($data['username'].'/profile');
        //create temp image folder
        Storage::makeDirectory($data['username'].'/profile/temp_img');
        //crate banner folder
        Storage::makeDirectory($data['username'].'/banner');
        
        return $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('dashboard.auth.register');
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('dashboard');
    }
}
