<?php

namespace App\Http\Controllers\Api\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Employee\EmployeeTransfer;
use App\Models\Utils;

class EmployeeTransferController extends Controller {
    
    public $empTransferModel;
	public $utilsModel;

	public function __construct() {

		$this->empTransferModel = new EmployeeTransfer();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->empTransferModel->getEmpTransferS($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->empTransferModel->createEmpTransfer($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->empTransferModel->updateEmpTransfer($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->empTransferModel->deleteEmpTransfer($id, $this->utilsModel->CALL_TYPE_API);
    }
}
