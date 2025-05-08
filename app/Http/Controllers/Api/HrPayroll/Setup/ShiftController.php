<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Shift;
use App\Models\Utils;

class ShiftController extends Controller {
    
    public $attShiftModel;
	public $utilsModel;

	public function __construct() {

		$this->attShiftModel = new Shift();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attShiftModel->getShifts($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attShiftModel->createShift($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attShiftModel->updateShift($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attShiftModel->deleteShift($id, $this->utilsModel->CALL_TYPE_API);
    }
}
