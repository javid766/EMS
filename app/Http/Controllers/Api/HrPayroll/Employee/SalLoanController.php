<?php

namespace App\Http\Controllers\Api\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\HrPayroll\Employee\SalLoan;
use App\Models\Utils;

class SalLoanController extends Controller {
    
    public $salLoanModel;
	public $utilsModel;

	public function __construct() {

		$this->salLoanModel = new SalLoan();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->salLoanModel->getSalLoans($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->salLoanModel->createSalLoan($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {


    	return $this->salLoanModel->updateSalLoan($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->salLoanModel->deleteSalLoan($id, $this->utilsModel->CALL_TYPE_API);
    }
}
