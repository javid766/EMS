<?php

namespace App\Http\Controllers\Api\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\HrPayroll\Employee\SalAdvance;
use App\Models\Utils;

class SalAdvanceController extends Controller {
    
    public $salAdvanceModel;
	public $utilsModel;

	public function __construct() {

		$this->salAdvanceModel = new SalAdvance();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->salAdvanceModel->getSalAdvances($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->salAdvanceModel->createSalAdvance($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {


    	return $this->salAdvanceModel->updateSalAdvance($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->salAdvanceModel->deleteSalAdvance($id, $this->utilsModel->CALL_TYPE_API);
    }
}
