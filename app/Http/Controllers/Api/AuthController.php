<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;

use App\User;
use Auth;

class AuthController extends Controller {

    public $userModel;

    public function __construct() {

        $this->userModel = new User();
    }

    public function login(Request $request) {

        $validData = $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string'
        ]);

        if(!Auth::attempt(['email' => $validData['email'], 'password' => $validData['password'], 'isactive' => 1])) {

            $this->logout($request);

            return response([
                'message' => 'Invalid credentials or user account is deactivated!',
                'status'  => 'error'
            ]);

        } else {

            // User::saveUserDataToSession($request, Auth::user());
            $accessToken = Auth::user()->createToken('authToken')->accessToken;

            $user = Auth::user();

            $userData = $this->userModel->getUserSessionFields($request, $user);
            $userMenu = $this->userModel->getUserMainMenu($user);

            $imgPath = url('images/employee/profilepicture/'.$user->profile_pic);

            return  response([
                'user' => Auth::user(),
                'user_pp' => $imgPath,
                'access_token' => $accessToken,
                'sessions' => count($userData) > 0 ? $userData[0] : null,
                'menu' => $userMenu,
                'status'  => 'success'
            ]);
        }
    }

    public function profile(Request $request) {

        $user = Auth::user();
        $roles = $user->getRoleNames();
        $permission = $user->getAllPermissions();

        return response([
            'user' => $user,
            'status'  => 'success'
        ]);
    }

    public function changePassword(Request $request) {

        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        // match old password
        if (Hash::check($request->old_password, Auth::user()->password)){

            User::find(auth()->user()->id)
            ->update([
                'password'=> Hash::make($request->password)
            ]);

            return response([
                'message' => 'Password has been changed',
                'status'  => 'success'
            ]);
        }
        
        return response([
            'message' => 'Password not matched!',
            'status'  => 'error'
        ]);
    }

    public function updateProfile(Request $request) {

        $validData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email'
        ]);

        $user = Auth::user();

        // check unique email except this user
        if(isset($request->email)) {

            $check = User::where('email', $request->email)
                     ->where('id', '!=', $user->id)
                     ->count();

            if($check > 0) {

                return response([
                    'message' => 'The email address is already used!',
                    'success' => 'error'
                ]);
            }
        }

        $user->update($validData);

        return response([
            'message' => 'Profile updated successfully!',
            'status'  => 'success'
        ]);
    }

    public function logout(Request $request) {
        
        if (Auth::user()) {
            
            $user = Auth::user()->token();
            $user->revoke();
        }

        return response([
            'message' => 'Logged out succesfully!',
            'status'  => 'success'
        ]);
    }
}
