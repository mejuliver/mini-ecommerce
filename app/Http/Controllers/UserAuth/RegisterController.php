<?php

namespace App\Http\Controllers\UserAuth;

use App\User;
use App\profile;
use App\roles_id;
use App\role;
use App\notifications;
use App\permissions_id;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

//use facade storage
use Illuminate\Support\Facades\Storage;


//use facades redis
use Illuminate\Support\Facades\Redis;

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
    protected $redirectTo = '/user/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('user.guest');
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
        ]);    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        //create user
        $user = User::create([
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
            'status' => 'active',
        ]);
        //create roles
        roles_id::create([
            'role_id' => 2,
            'username' => $data['username'],
        ]);

        //create permission
        permissions_id::create([
            'perm_id' => 1,
            'username' => $data['username'],
        ]);

        //create user folder
        Storage::makeDirectory($data['username']);
        //create profile folder
        Storage::makeDirectory($data['username'].'/profile');
        //create temp image folder
        Storage::makeDirectory($data['username'].'/profile/temp_img');
        //crate banner folder
        Storage::makeDirectory($data['username'].'/banner');


        //get the username to those who have an admin username
        $admins = [];
        foreach(roles_id::where('role_id',role::where('role_name','admin')->first()->role_id)->get() as $a){

            array_push($admins, $a->username);

            //save to notification table
            $not = new notifications();
            $not->username= $a->username;
            $not->sender = 'system';
            $not->contents = '<b>'.$data['first_name'].' '.$data['last_name'].'</b> has created his/her account';
            $not->save();
        }

        //send notification
        $data = [
            'event' => 'notification',
            'contents' => '<b>'.$data['first_name'].' '.$data['last_name'].'</b> has created his/her account',
            'to' => $admins,
            'type' => 'notification',
            'sender' => 'system',
        ];
 
        Redis::publish('notifications',json_encode($data));

        //session(['register' => true ]);

        return $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('user.auth.login');
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('user');
    }
}
