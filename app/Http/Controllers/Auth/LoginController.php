<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Log;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')
        //-> scopes(["age_range"])
        ->scopes(["email","user_birthday","user_gender","user_hometown","user_location","user_friends","user_age_range"])
        ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try{
        $auth_user = Socialite::driver('facebook')->user();
        //dd($auth_user);
        //Log::info($auth_user->user_gender);
        $user = User::updateOrCreate(
            [
                'email'=> $auth_user->email
            ],
            [
                'token'=> $auth_user->token,
                'name'=> $auth_user->name
            ]
            );
        Auth::login($user,true);
        return redirect()->to('/home');  
        }catch(Exception $ex){
            Log::error("An unexpected error ". $ex);
        }
      
        //dd($user); 

        //return redirect()->action('HomeController@index');

    }
}
