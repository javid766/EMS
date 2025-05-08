<?php

namespace App\Http\Controllers\HrPayroll\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utils;
use App\Models\HrPayroll\Setup\Location;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\HrPayroll\Setup\EType;
use App\User;
use DataTables,Auth;

class MonthlyDeductionReportController extends Controller
{

	public $utilsModel;
	public $deptModel;
	public $locationtModel;
	public $attEtypeModel;

	public function __construct() {

		$this->locationtModel = new Location();
		$this->deptModel  = new Dept();
		$this->attEtypeModel = new EType();
		$this->utilsModel = new Utils();
	}

	public function index(Request $request){

		$locations   = $this->getLocations($request);
		$departments = $this->getDepartments($request);
		$eTypes 	 = $this->getETypes($request);
		return view('hrpayroll.reports.monthy_deduction_report',compact('locations','departments','eTypes'));
	}

	public function getLocations(Request $request, $id=0){

		return $this->locationtModel->getLocations($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->pluck('vname','id');
	}

	public function getDepartments(Request $request, $id=0){

		return $this->deptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->pluck('vname','id');
	}

	public function getETypes(Request $request, $id=0){
		return $this->attEtypeModel->getETypes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->pluck('vname','id');
	}
}
