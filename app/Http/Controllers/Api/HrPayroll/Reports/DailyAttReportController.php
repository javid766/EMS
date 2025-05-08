<?php

namespace App\Http\Controllers\Api\HrPayroll\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Reports\DailyAttReport;
use App\Models\Utils;

class DailyAttReportController extends Controller {
    
    public $attListingReportModel ;
	public $utilsModel;

	public function __construct() {

		$this->attListingReportModel  = new DailyAttReport();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attListingReportModel->getAttListing($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

}
