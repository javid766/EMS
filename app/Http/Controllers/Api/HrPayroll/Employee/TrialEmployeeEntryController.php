<?php

namespace App\Http\Controllers\Api\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Employee\TrialEmployee;
use App\Models\Utils;

class TrialEmployeeEntryController extends Controller {
    
    public $trialEmpModel;
	public $utilsModel;

	public function __construct() {

		$this->trialEmpModel = new TrialEmployee();
		$this->utilsModel = new Utils();
	}

   public function list(Request $request, $id = 0) {

    	return $this->trialEmpModel->getTrialEmployees($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->trialEmpModel->createTrialEmployees($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->trialEmpModel->updateTrialEmployees($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->trialEmpModel->deleteTrialEmployees($id, $this->utilsModel->CALL_TYPE_API);
    }
}
