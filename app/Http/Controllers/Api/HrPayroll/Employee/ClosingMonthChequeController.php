<?php

namespace App\Http\Controllers\Api\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\HrPayroll\Employee\ClosingMonthCheque;
use App\Models\Utils;

class ClosingMonthChequeController extends Controller
{
    public $closingMonthChequeModel;
	public $utilsModel;

	public function __construct() {

		$this->closingMonthChequeModel = new ClosingMonthCheque();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {
    	
    	return $this->closingMonthChequeModel->getClosingMonthCheques($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {
    	
    	return $this->closingMonthChequeModel->createClosingMonthCheque($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {
    	
    	return $this->closingMonthChequeModel->updateClosingMonthCheque($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->closingMonthChequeModel->deleteClosingMonthCheque($id, $this->utilsModel->CALL_TYPE_API);
    }
}
