<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Weekday;
use App\Models\Utils;

class WeekdayController extends Controller {
    
    public $attWeekdayModel;
	public $utilsModel;

	public function __construct() {

		$this->attWeekdayModel = new Weekday();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attWeekdayModel->getWeekdays($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attWeekdayModel->createWeekday($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attWeekdayModel->updateWeekday($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attWeekdayModel->deleteWeekday($id, $this->utilsModel->CALL_TYPE_API);
    }
}
