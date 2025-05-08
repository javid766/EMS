<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\LeaveBalance;
use App\Models\Utils;

class LeaveBalanceController extends Controller {
    
    public $attLeaveBalanceModel;
	public $utilsModel;

	public function __construct() {

		$this->attLeaveBalanceModel = new LeaveBalance();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attLeaveBalanceModel->getLeaveBalances($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attLeaveBalanceModel->createLeaveBalance($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attLeaveBalanceModel->updateLeaveBalance($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attLeaveBalanceModel->deleteLeaveBalance($id, $this->utilsModel->CALL_TYPE_API);
    }
}
