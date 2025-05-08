<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\LoanTypes;
use App\Models\Utils;

class LoanTypesController extends Controller {
    
    public $loanTypesModel;
	public $utilsModel;

	public function __construct() {

		$this->loanTypesModel = new LoanTypes();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->loanTypesModel->getLoanTypes($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->loanTypesModel->createLoanTypes($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->loanTypesModel->updateLoanTypes($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->loanTypesModel->deleteLoanTypes($id, $this->utilsModel->CALL_TYPE_API);
    }
}
