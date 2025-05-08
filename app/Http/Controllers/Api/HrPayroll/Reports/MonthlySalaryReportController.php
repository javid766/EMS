<?php

namespace App\Http\Controllers\Api\HrPayroll\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Reports\MonthlySalReport;
use App\Models\Utils;

class MonthlySalaryReportController extends Controller {
    
    public $attMonthlySalReportModel;
	public $utilsModel;

	public function __construct() {

		$this->attMonthlySalReportModel  = new MonthlySalReport();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attMonthlySalReportModel->getMonthlySal($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

}
