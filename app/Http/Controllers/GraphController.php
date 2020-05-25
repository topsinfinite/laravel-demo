<?php

namespace App\Http\Controllers;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class GraphController extends Controller
{
    private $api;
    public function __construct(Facebook $fb)
    {
        $this->middleware(function ($request, $next) use ($fb) {
            $fb->setDefaultAccessToken(Auth::user()->token);
            $this->api = $fb;
            return $next($request);
        });
    }

    public function retrieveUserProfile(){
        try {
           $params = "birthday,name,first_name,last_name,age_range,link,gender,hometown,friends,email,location,verified";
          // $params = "name,first_name,last_name";
            $user = $this->api->get('/me?fields='.$params)->getGraphUser();
            dd($user);
 
        } catch (FacebookSDKException $e) {
            Log::info("Facebook SDK error: ".$e);
        }
 
    }
}
