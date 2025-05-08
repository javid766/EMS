<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\LeaveType;
use App\Models\Utils;

class LeaveTypeController extends Controller
{
    public $attLeaveTypeModel;
	public $utilsModel;

	public function __construct() {

		$this->attLeaveTypeModel = new LeaveType();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attLeaveTypeModel->getLeaveTypes($request, $id, $this->utilsModel->CALL_TYPE_API);
    }
}
