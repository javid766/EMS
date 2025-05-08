<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use App\User;
use Auth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user) {
        $data = request()->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'isactive' => 1]))
        {
            User::saveUserDataToSession($request, Auth::user());
            $userid = $request->session()->get('userid');
            $user_flag = User::where('id',$userid)->pluck('user_flag')->first();
            $request->session()->put('user_flag', $user_flag);
            return redirect('/dashboard');
        }

        $this->logout($request);

        session(['message' => 'Username or password is incorrect, or you account is deactivated']);
        
        return redirect()->back();

    }

    // custom logout function
    // redirect to login page
    public function logout(Request $request)
    {   
        $userid = $request->session()->get('userid');
        $user_flag = User::where('id',$userid)->update(['user_flag'=> 0]);
        $request->session()->put('user_flag', 0);

        $this->guard()->logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();
        

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect('/');
    }
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'exists:users,' . $this->username() . ',isactive,1',
            'password' => 'required|string',
        ]);
    }
}
