<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User\UserCompany;
use App\Models\Utils;

class UserCompanyController extends Controller {

    public $userCompanyModel;
    public $utilsModel;

    public function __construct() {

        $this->userCompanyModel = new UserCompany();
        $this->utilsModel = new Utils();
    }

    public function list(Request $request, $id = 0) {

        return $this->userCompanyModel->getUserCompanies($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

        return $this->userCompanyModel->createUserCompany($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

        return $this->userCompanyModel->updateUserCompany($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
        
        return $this->userCompanyModel->deleteUserCompany($id, $this->utilsModel->CALL_TYPE_API);
    }
}
