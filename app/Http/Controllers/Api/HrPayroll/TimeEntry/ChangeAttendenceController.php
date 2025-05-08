<?php

namespace App\Http\Controllers\Api\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\TimeEntry\ChangeAttendence;
use App\Models\Utils;

class ChangeAttendenceController extends Controller {
    
    public $changeAttendanceModel;
	public $utilsModel;

	public function __construct() {

		$this->changeAttendanceModel = new ChangeAttendence();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->changeAttendanceModel->getEmpChangeAttendence($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

	public function create(Request $request) {

        return $this->changeAttendanceModel->createAttendanceEmployee($request, $this->utilsModel->CALL_TYPE_API);
    }
     
    public function update(Request $request, $id) {

    	return $this->changeAttendanceModel->updateAttendanceEmployee($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

}
