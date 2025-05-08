<?php

namespace App\Http\Controllers\Api\HrPayroll\Employee\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Employee\EmployeeReport;
use App\Models\Utils;

class EmployeeReportController extends Controller {
    
    public $empReportModel;
	public $utilsModel;

	public function __construct() {

		$this->empReportModel = new EmployeeReport();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->empReportModel->getEmpData($request, $id, $this->utilsModel->CALL_TYPE_API);
    }
}
