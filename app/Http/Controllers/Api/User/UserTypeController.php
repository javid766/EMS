<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User\UserType;
use App\Models\Utils;

class UserTypeController extends Controller {
    
    public $userTypeModel;
    public $utilsModel;

    public function __construct() {

        $this->userTypeModel = new UserType();
        $this->utilsModel = new Utils();
    }

    public function list(Request $request, $id = 0) {

        return $this->userTypeModel->getUserTypes($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

        return $this->userTypeModel->createUserType($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

        return $this->userTypeModel->updateUserType($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {

        return $this->userTypeModel->deleteUserType($id, $this->utilsModel->CALL_TYPE_API);
    }
}
