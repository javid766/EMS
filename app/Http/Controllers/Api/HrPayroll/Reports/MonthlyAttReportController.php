<?php

namespace App\Http\Controllers\Api\HrPayroll\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Reports\MonthlyAttReport;
use App\Models\Utils;

class MonthlyAttReportController extends Controller {
    
    public $attMonthlyReportModel;
	public $utilsModel;

	public function __construct() {

		$this->attMonthlyReportModel  = new MonthlyAttReport();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attMonthlyReportModel->getAttMonthly($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

}
