<?php

namespace App\Http\Controllers\HrPayroll\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Dashboard\Dashboard;
use App\Models\Utils;

class DashboardController extends Controller
{ 
	public $dashboardModel;
	public $utilsModel;
	public $FOR_MERGE;

	public function __construct() {

		$this->dashboardModel = new Dashboard();
		$this->utilsModel = new Utils();
		$this->FOR_MERGE = true;
	}

	public function index(Request $request){	
		if ($request->session()->get('dashboardInputdatefrom', 0) == 0) {
			$datein = date('Y-m-01');
			$dateout = date('Y-m-d');
			$request->request->add(['datein'   => $datein]);
			$request->request->add(['dateout'   => $dateout]);
			$request->session()->put('vdatefrom', $datein);
			$request->session()->put('vdateto', $dateout);
			$request->session()->put('dashboardInputdatefrom', date('01/m/Y'));
			$request->session()->put('dashboardInputdateto', date('d/m/Y'));
		}
		else{
			$request->request->add(['datein'   => $request->session()->get('vdatefrom', 0)]);
			$request->request->add(['dateout'   => $request->session()->get('vdateto', 0)]);
		}
		
		$deptWiseGroup = array();
		$attMain = $this->attMain($request);
		$attactivity = $this->attendanceActivity($request);
		if (sizeof($attactivity) !=0) {
			foreach ($attactivity as $key => $value) {
				$vdate = date("d/m/Y",strtotime($value->vdate)); 
	       	$value->vdate = $vdate;
			}
		}

		//current month
		$totalstrength = '';
		$deptWiseStrength = $this->deptWiseStrength($request);
		if (($deptWiseStrength)->toArray()) {
			$totalstrength = $deptWiseStrength[0]->totalstrength;
		}

		//Given Month Strength
		$monthWiseStrength = $this->monthWiseStrength($request);

		//Month wise
		$deptMonthWiseStrength = $this->deptMonthWiseStrength($request)->toArray();
		foreach ($deptMonthWiseStrength as $key => $value ) {
			$deptWiseGroup[$value['departmentname']][] = $value;
		}
		$empStrengthMonthWise = $this->employeeStrengthMonthWise($request);
		foreach ($empStrengthMonthWise as $key => $value) {
			$vdate = date("d/m/Y",strtotime($value->vdate)); 
       	$value->vdate = $vdate;
		}
		
		$monthWiseSalary = $this->monthWiseSalary($request);
		$deptmonthWiseSalary = $this->deptWiseSalary($request);
		foreach ($monthWiseSalary as $value) {
			$value['vdate'] = date("M", strtotime($value['vdate']));
		}
		
		return view('pages.dashboard.dashboard',compact('attMain','attactivity','deptWiseStrength','deptWiseGroup','monthWiseSalary','deptmonthWiseSalary','empStrengthMonthWise','monthWiseStrength','totalstrength'));
	}

	public function applyDateFilter(Request $request){

		$vdatefrom = $request->vdatefrom;
		$vdateto = $request->vdateto;
		$request->request->add(['datein'   => $vdatefrom]);
		$request->request->add(['dateout'   => $vdateto]);

		$request->session()->put('vdatefrom', $vdatefrom);
		$request->session()->put('vdateto', $vdateto);
		$dashboardInputdatefrom = date("d/m/Y",strtotime($vdatefrom));
		$dashboardInputdateto = date("d/m/Y",strtotime($vdateto));
		$request->session()->put('dashboardInputdatefrom', $dashboardInputdatefrom);
		$request->session()->put('dashboardInputdateto', $dashboardInputdateto);

		$deptWiseGroup = array();
		$attMain = $this->attMain($request);
		$attactivity = $this->attendanceActivity($request);
		foreach ($attactivity as $key => $value) {
			$vdate = date("d/m/Y",strtotime($value->vdate)); 
       	$value->vdate = $vdate;
		}
		
		//current month
		$totalstrength = '';
		$deptWiseStrength = $this->deptWiseStrength($request);
		if (($deptWiseStrength)->toArray()) {
			$totalstrength = $deptWiseStrength[0]->totalstrength;
		}
		//Given Month Strength
		$monthWiseStrength = $this->monthWiseStrength($request);
		//Month wise
		$deptMonthWiseStrength = $this->deptMonthWiseStrength($request)->toArray();
		foreach ($deptMonthWiseStrength as $key => $value ) {
			$deptWiseGroup[$value['departmentname']][] = $value;
		}
		
		$empStrengthMonthWise = $this->employeeStrengthMonthWise($request);
		foreach ($empStrengthMonthWise as $key => $value) {
			$vdate = date("d/m/Y",strtotime($value->vdate)); 
       	$value->vdate = $vdate;
		}
		
		$monthWiseSalary = $this->monthWiseSalary($request);
		$deptmonthWiseSalary = $this->deptWiseSalary($request);
		foreach ($monthWiseSalary as $value) {
			$value['vdate'] = date("M", strtotime($value['vdate']));
		}
		
		return redirect()->back()->with(compact('attMain','attactivity','deptWiseStrength','deptWiseGroup','monthWiseSalary','deptmonthWiseSalary','empStrengthMonthWise','monthWiseStrength','totalstrength'));
	}

	public function attMain(Request $request, $forMerge = false) {
		return $this->dashboardModel->getAttMain($request, $this->utilsModel->CALL_TYPE_DEFAULT);
	}

	public function attendanceActivity(Request $request, $forMerge = false) {
         $request->request->add([
         	'employeeid'   =>  0
        ]);

		return $this->dashboardModel->getAttendanceActivity($request, $this->utilsModel->CALL_TYPE_DEFAULT);
	}
	// Current Month Dept Strength
	public function deptWiseStrength(Request $request, $forMerge = false) {

		return $this->dashboardModel->getDeptWiseStrength($request, $this->utilsModel->CALL_TYPE_DEFAULT);
	}
	// Monthwise Dept Strength
	public function deptMonthWiseStrength(Request $request, $forMerge = false) {

		return $this->dashboardModel->getDeptMonthWiseStrength($request, $this->utilsModel->CALL_TYPE_DEFAULT);
	}

	public function monthWiseSalary(Request $request, $forMerge = false) {

		return $this->dashboardModel->getMonthWiseSalary($request, $this->utilsModel->CALL_TYPE_DEFAULT);
	}

	public function deptWiseSalary(Request $request, $forMerge = false) {

		return $this->dashboardModel->getDeptWiseSalary($request, $this->utilsModel->CALL_TYPE_DEFAULT);
	}

	public function employeeStrengthMonthWise(Request $request, $forMerge = false) {

		return $this->dashboardModel->getEmpMonthWiseStrength($request, $this->utilsModel->CALL_TYPE_DEFAULT);
	}
	public function monthWiseStrength(Request $request, $forMerge = false) {

		return $this->dashboardModel->getMonthWiseStrength($request, $this->utilsModel->CALL_TYPE_DEFAULT);
	}

}
