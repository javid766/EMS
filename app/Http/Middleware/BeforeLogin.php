<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\Utils;
use App\User;
use App\Models\HrPayroll\Setup\Tenant;

class BeforeLogin
{

    public $utilsModel;

    public function __construct() {
        $this->utilsModel = new Utils();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        $email = $request->get('email');
        $password = $request->get('password');

        $tenant = Tenant::select('tpassword')->where('tlogin', $email)->first();

        $hashedPassword = "";

        if (!$tenant) {

            $user = User::select('password')->where('email', $email)->first();

            if ($user && Hash::check($password, $user->password)) {

                $hashedPassword = $user->password;
            }

        } else if ($tenant && Hash::check($password, $tenant->tpassword)) {

            $hashedPassword = $tenant->tpassword;
        }

        $checkBeforeLogin = DB::select("CALL sp_user_first_get('". $email ."', '". $hashedPassword ."')");

        $resturnedMsg = get_object_vars($checkBeforeLogin[0])['@result'];

        if ($resturnedMsg == $this->utilsModel->FIRST_LOGIN_WELCOME) {

            $request->session()->put('isFirstLogin', true);
            return  $next($request);

        } else if ($resturnedMsg == $this->utilsModel->FIRST_LOGIN_EXISTS) {

            $request->session()->put('isFirstLogin', false);
            return  $next($request); 

        } else {

            return redirect('/login')->with('error', $resturnedMsg);
        }
    }
}
