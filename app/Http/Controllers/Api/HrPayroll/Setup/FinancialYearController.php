<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\FinancialYear;
use App\Models\Utils;

class FinancialYearController extends Controller {
    
    public $accFinancialYearModel;
	public $utilsModel;

	public function __construct() {

		$this->accFinancialYearModel = new FinancialYear();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->accFinancialYearModel->getAccFinancialYears($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->accFinancialYearModel->createAccFinancialYear($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->accFinancialYearModel->updateAccFinancialYear($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->accFinancialYearModel->deleteAccFinancialYear($id, $this->utilsModel->CALL_TYPE_API);
    }
}
