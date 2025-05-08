<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Holiday;
use App\Models\Utils;

class HolidayController extends Controller {
    
    public $attHolidayModel;
	public $utilsModel;

	public function __construct() {

		$this->attHolidayModel = new Holiday();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attHolidayModel->getHolidays($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attHolidayModel->createHoliday($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attHolidayModel->updateHoliday($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attHolidayModel->deleteHoliday($id, $this->utilsModel->CALL_TYPE_API);
    }
}
