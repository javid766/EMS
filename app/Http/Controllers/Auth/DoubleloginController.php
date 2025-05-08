<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use App\User;
use Auth;

class DoubleloginController extends Controller
{
    use AuthenticatesUsers;

    // custom logout function
    // redirect to login page
    public function doublelogin(Request $request)
    {   
        $userid = $request->session()->get('userid');
        $user_flag = User::where('id',$userid)->update(['user_flag'=> 1]);
        $request->session()->put('user_flag',1);

        $this->guard()->logout();
        

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect('/');
    }
}
