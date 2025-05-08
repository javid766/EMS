<?php

namespace App\Http\Controllers\Api\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\Utils;

class EmployeeInfoController extends Controller {
    
    public $empInfoModel;
	public $utilsModel;

	public function __construct() {

		$this->empInfoModel = new EmployeeInfo();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->empInfoModel->getEmployees($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->empInfoModel->createEmployees($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->empInfoModel->updateEmployees($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->empInfoModel->deleteEmployees($id, $this->utilsModel->CALL_TYPE_API);
    }
}
