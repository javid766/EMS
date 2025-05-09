<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\RosterShift;
use App\Models\Utils;

class RosterShiftController extends Controller {
    
    public $attRosterShiftModel;
	public $utilsModel;

	public function __construct() {

		$this->attRosterShiftModel = new RosterShift();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attRosterShiftModel->getRosterShifts($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attRosterShiftModel->createRosterShift($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attRosterShiftModel->updateRosterShift($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attRosterShiftModel->deleteRosterShift($id, $this->utilsModel->CALL_TYPE_API);
    }
}
