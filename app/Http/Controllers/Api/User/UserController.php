<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\Utils;
use App\User;
use Auth;

class UserController extends Controller {

    public $userModel;

    public function __construct() {

        $this->userModel = new User();
        $this->utilsModel = new Utils();
    }

    public function list(Request $request, $id = 0) {

        return $this->userModel->getUsers($id, $request, $this->utilsModel->CALL_TYPE_API);
    }


    public function store(Request $request) {

        return $this->userModel->createUser($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id, Request $request) {

        return $this->userModel->deleteUser($id, $this->utilsModel->CALL_TYPE_API);
    }

    public function update($id, Request $request) {

        return $this->userModel->updateUser($id, $request, $this->utilsModel->CALL_TYPE_API);
    }

    public function changePassword(Request $request) {

        return $this->userModel->changePassword($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function changeRole($id,Request $request) {

        $request->validate([
            'roles'     => 'required'
        ]);
        
        // update user roles
        $user = User::find($id);

        if($user) {

            // assign role to user
            $user->syncRoles($request->roles); 
               
            return response([
                'message' => 'Roles changed successfully!',
                'success' => 1
            ]);
        }

        return response([
            'message' => 'Sorry! User not found',
            'success' => 0
        ]);
    }
}
