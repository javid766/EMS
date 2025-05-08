<?php

namespace App\Http\Controllers\Api\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Employee\EmployeeFixTax;
use App\Models\Utils;

class FixTaxController extends Controller {
    
    public $empFixTaxModel;
	public $utilsModel;

	public function __construct() {

		$this->empFixTaxModel = new EmployeeFixTax();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->empFixTaxModel->getEmpFixTax($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->empFixTaxModel->createEmpFixTax($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->empFixTaxModel->updateEmpFixTax($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->empFixTaxModel->deleteEmpFixTax($id, $this->utilsModel->CALL_TYPE_API);
    }
}
