<?php

namespace App\Http\Controllers\Api\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\TimeEntry\OTEntryDaily;
use App\Models\Utils;

class OTEntryDailyController extends Controller {
    
    public $dailyManualOtModel;
	public $utilsModel;

	public function __construct() {

		$this->dailyManualOtModel = new OTEntryDaily();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->dailyManualOtModel->getDailyManualOT($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

	public function create(Request $request) {

        return $this->dailyManualOtModel->createDailyManualOT($request, $this->utilsModel->CALL_TYPE_API);
    }
     
    public function update(Request $request, $id) {

    	return $this->dailyManualOtModel->updateDailyManualOT($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

}
