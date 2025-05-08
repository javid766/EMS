<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User\UserLocation;
use App\Models\Utils;

class UserLocationController extends Controller {

    public $userLocationModel;
    public $utilsModel;

    public function __construct() {

        $this->userLocationModel = new UserLocation();
        $this->utilsModel = new Utils();
    }

    public function list(Request $request, $id = 0) {

        return $this->userLocationModel->getUserLocations($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

        return $this->userLocationModel->createUserLocation($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

        return $this->userLocationModel->updateUserLocation($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {

        return $this->userLocationModel->deleteUserLocation($id, $this->utilsModel->CALL_TYPE_API);
    }
}
