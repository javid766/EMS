<?php

namespace App\Http\Controllers\HrPayroll\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utils;
use App\Models\HrPayroll\Setup\Location;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\Reports\MonthlySalReport;
use App\Models\HrPayroll\Setup\EType;
use App\User;
use DataTables,Auth;
use \PDF;
use Response;

class MonthlySalaryReportController extends Controller
{
	public $utilsModel;
	public $deptModel;
	public $locationtModel;
	public $attEtypeModel;
	public $attMonthlySalReportModel;

	public function __construct() {
		$this->locationtModel = new Location();
		$this->deptModel  = new Dept();
		$this->attEtypeModel = new EType();
		$this->employeeInfoModel = new EmployeeInfo();
		$this->attMonthlySalReportModel  = new MonthlySalReport();
		$this->utilsModel = new Utils();
	}

	public function index(Request $request){	
		$locations   = $this->getLocations($request);
		$departments = $this->getDepartments($request);
		$eTypes 	 = $this->getETypes($request);
		$empNames = $this->getEmployees($request);
		return view('hrpayroll.reports.monthy_salary_report',compact('locations','departments','eTypes','empNames'));
	}

	public function getData($request, $id=0){
		return $this->attMonthlySalReportModel->getMonthlySal($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
	}

	public function fillGrid(Request $request){
		$attfilter = $request->attfilter;
		$first_date_find = strtotime(date("Y-m-d", strtotime($request->datein)) . ", first day of this month");
		$first_date = date("Y-m-d",$first_date_find);
		$request->request->add(['datefrom' => $first_date]);
		$last_date_find = strtotime(date("Y-m-d", strtotime($request->datein)) . ", last day of this month");
		$last_date = date("Y-m-d",$last_date_find);
		$request->request->add(['dateto' => $last_date]);

		$deptid = '';
		$cwhere = '';
		if ($request->deptid != '') {
			$deptid = implode(",",$request->deptid);
		}
		
		$companyid = $request->session()->get('companyid', 0);
		$cwhere = ' WHERE E.companyid = '.$companyid.'';

		if($attfilter == 'salsheetcash' || $attfilter == 'salsheetcheque' || $attfilter == 'salsheetcomplete' || $attfilter == 'finalsettlement' || $attfilter == 'advancedeductionsheet' || $attfilter == 'advancedeductionsummary'){
			if ($request->employeeid !='' ) {
				$cwhere = $cwhere . ' AND E.id = '.$request->employeeid .'';
			}
		}

		if($attfilter == 'empsalaryhistory'){
		     if ($request->employeeid !='' ) {
			 	$cwhere = $cwhere . ' AND E.employeeid = '.$request->employeeid .'';
			}
		}

		if ($request->locationid !='') {
			$cwhere = $cwhere . ' AND E.locationid = '. $request->locationid .'';
		}

		if ($deptid !='') {
			$cwhere = $cwhere . ' AND E.deptid IN ('. $deptid .')';
		}
		if($request->etypeid !='') {
			$cwhere = $cwhere .' AND E.etypeid = '.$request->etypeid.'';
		}

		if($attfilter == 'salsheetcash'){
			$cwhere = $cwhere .' AND H.isbank = 0 AND H.vdate BETWEEN "'.$first_date.'" AND  "'.$last_date.'" ';
		}

		if($attfilter == 'salsheetcheque'){
			$cwhere = $cwhere .' AND H.isbank = 1 AND H.vdate BETWEEN "'.$first_date.'" AND  "'.$last_date.'"';
		}

		if($attfilter == 'salsheetcomplete'){
			$cwhere = $cwhere .' AND H.vdate BETWEEN "'.$first_date.'" AND  "'.$last_date.'"';
		}

		if($attfilter == 'finalsettlement'){
			$cwhere = $cwhere .' AND H.dol BETWEEN "'.$first_date.'" AND  "'.$last_date.'" AND H.vdate BETWEEN "'.$first_date.'" AND  "'.$last_date.'"';
		}
 		
 		if($attfilter == 'salarysummarycash'){
			$request->request->add(['vtype' => 'summary']);
			$cwhere = $cwhere .' AND H.isbank = 0 AND H.vdate BETWEEN "'.$first_date.'" AND  "'.$last_date.'" ';
		}

		if($attfilter == 'salarysummarycheque'){
			$request->request->add(['vtype' => 'summary']);
			$cwhere = $cwhere .' AND H.isbank = 1 AND H.vdate BETWEEN "'.$first_date.'" AND  "'.$last_date.'"';
		}

		if($attfilter == 'summarycomplete'){
			$request->request->add(['vtype' => 'summary']);
			$cwhere = $cwhere .' AND H.vdate BETWEEN "'.$first_date.'" AND  "'.$last_date.'"';
		}

		if($attfilter == 'finalsettlementsummary'){
			$request->request->add(['vtype' => 'summary']);
			$cwhere = $cwhere .' AND H.dol BETWEEN "'.$first_date.'" AND  "'.$last_date.'" AND H.vdate BETWEEN "'.$first_date.'" AND  "'.$last_date.'"';
		}	
		
		if ($attfilter == 'advancedeductionsheet' || $attfilter == 'advancedeductionsummary'){
			$cwhere = $cwhere .' AND H.posteddate BETWEEN "'.$first_date.'" AND  "'.$last_date.'"';
		}

		$request->request->add(['cwhere'   => $cwhere]);
		$request->request->add(['attfilter' => $attfilter]);

		$modelData = $this->getData($request);

		if ($attfilter == 'salsheetcash' || $attfilter == 'salsheetcheque' || $attfilter == 'salsheetcomplete' || $attfilter == 'finalsettlement'){
		    foreach ($modelData as $key => $value) {
		    	$value->employeename = $value->employeename."  (". $value->cnicno. ")";
			$doj = date("d/m/Y",strtotime($value->doj)); 
			$value->designation = $value->designation." - ". $doj;
			$value->datefrom = date("d/m/Y",strtotime($value->datefrom)); 
			}
		}

		if ($attfilter == 'empsalaryhistory'){
			foreach ($modelData as $key => $value) {
				$doj =  date("d-M-Y",strtotime($value->doj)); 
				$value->vdate  = date('M-Y', strtotime($value->vdate)); 
				$value->employeename = "E-Code: ". $value->empcode. " , Name: ". $value->employeename
				. " , Department: ". $value->department. " , Designation: ". $value->designation ." , DOJ: ".$doj;  
			}
		}
  
		return Datatables::of($modelData)->make(true);
	}

	public function getLocations(Request $request, $id=0){
		$locations = $this->locationtModel->getLocations($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($locations);
        return $locations;
	}

	public function getDepartments(Request $request, $id=0){
		$deptsData = $this->deptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($deptsData);
        return $deptsData;
	}
	public function getETypes(Request $request, $id=0){
		$etypes = $this->attEtypeModel->getETypes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($etypes);
        return $etypes;
	}

	public function getEmployees(Request $request, $id=0){
        $request->request->add(['isactive' => '-1']); //add request
        $employeeInfoData = array();
        $employeeInfo = $this->employeeInfoModel->getEmployees($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        foreach ($employeeInfo as $value) {
        	$employeeInfoData[$value['id']] = $value['employeename']." ".$value['fathername']." (".$value['empcode'] .") ";
        }
        natcasesort($employeeInfoData);
        return $employeeInfoData;
	}
	public function setsession(Request $request){
	    $request->session()->put('salemployeeid', $request->employeeid);
	    $request->session()->put('salvdate', $request->datein);
        $request->session()->put('saletypeid', $request->etypeid);
        $request->session()->put('sallocationid', $request->locationid);
        $request->session()->put('saldeptid', $request->deptid);
	}
	    
	public function SalarySlip(Request $request){

	   	$salemployeeid = $request->session()->get('salemployeeid', 0);
		$salvdate      = $request->session()->get('salvdate', 0);
		$saletypeid    = $request->session()->get('saletypeid', 0);
		$sallocationid = $request->session()->get('sallocationid', 0);
		$saldeptid     = $request->session()->get('saldeptid', 0);
		$companyid     = $request->session()->get('companyid', 0);
		$sdate = $salvdate;
		$first_date_find = strtotime(date("Y-m-d", strtotime($salvdate)) . ", first day of this month");
		$first_date = date("Y-m-d",$first_date_find);
		$last_date_find = strtotime(date("Y-m-d", strtotime($salvdate)) . ", last day of this month");
		$last_date = date("Y-m-d",$last_date_find);

	   	$cwhere = 'WHERE H.companyid = '.$companyid.'';
		if ($salemployeeid !='') {
	         $cwhere = $cwhere. ' AND H.employeeid = '.$salemployeeid.'';
	    }
	    if ($salvdate !='') {
	         $cwhere = $cwhere. ' AND H.vdate BETWEEN "'.$first_date.'" AND  "'.$last_date.'"';
	    }
	    if ($saletypeid !='') {
	         $cwhere = $cwhere. ' AND H.etypeid = '.$saletypeid.'';
	    }
	    if ($sallocationid !='') {
	         $cwhere = $cwhere. ' AND H.locationid = '.$sallocationid.'';
	    }
	    if ($saldeptid !='') {
	         $cwhere = $cwhere. ' AND H.deptid = '.$saldeptid.'';
	    }
	    $request->request->add(['vdate' => $first_date]);
	    $request->request->add(['cwhere'   => $cwhere]);

	    $salSlipData = $this->attMonthlySalReportModel->getSalarySlip($request,$salemployeeid, $this->utilsModel->CALL_TYPE_DEFAULT);
	    $salvdate = date("M-Y", strtotime($sdate));

	    $data = ['salSlipData' => $salSlipData,'salvdate' => $salvdate];
	    $pdf = PDF::loadView('hrpayroll.reports.monthly_salary_slip_pdf', $data);
	    $filename = 'salaryslip.pdf';
	    $pdf->save(base_path('SalarySlip/'.$filename)); 
	    $path = base_path('SalarySlip/'.$filename);
	    return Response::make(file_get_contents($path), 200, [
	            'Content-Type' => 'application/pdf',
	            'Content-Disposition' => 'inline; filename="'.$filename.'"'
	        ]);
	}
}
