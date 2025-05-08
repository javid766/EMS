<?php

namespace App\Http\Controllers\Api\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Employee\LoanEntry;
use App\Models\Utils;

class LoanEntryController extends Controller {
    
    public $loanEntryModel;
	public $utilsModel;

	public function __construct() {
		$this->loanEntryModel = new LoanEntry();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {
    	return $this->loanEntryModel->getLoanEntries($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {
    	return $this->loanEntryModel->createLoanEntry($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {
    	return $this->loanEntryModel->updateLoanEntry($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {    	
    	return $this->loanEntryModel->deleteLoanEntry($id, $this->utilsModel->CALL_TYPE_API);
    }
}
