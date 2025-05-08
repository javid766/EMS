<?php

namespace App\Http\Controllers\HrPayroll\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utils;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\Setup\Location;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\HrPayroll\Setup\EType;
use App\Models\HrPayroll\Reports\DailyAttReport;
use App\User;
use DataTables,Auth;
use Log;
class DailyAttReportController extends Controller
{

	public $utilsModel;
	public $deptModel;
	public $locationtModel;
	public $attEtypeModel;
	public $employeeInfoModel;
	public $attListingReportModel ;

	public function __construct() {

		$this->locationtModel = new Location();
		$this->deptModel  = new Dept();
		$this->attEtypeModel = new EType();
		$this->employeeInfoModel = new EmployeeInfo();
		$this->attListingReportModel  = new DailyAttReport();
		$this->utilsModel = new Utils();
	}

	public function index(Request $request){

		$locations = $this->getLocations($request);
		$departments = $this->getDepartments($request);
		$eTypes 	 = $this->getETypes($request);
		$empNames = $this->getEmployees($request);
		$bool  = array('Yes','No' );
		return view('hrpayroll.reports.att_listing_report',compact('locations','departments','eTypes','bool','empNames'));
	}

	public function getData($request, $id=0){

		return $this->attListingReportModel->getAttListing($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
	}

	public function fillGrid(Request $request){
		
		$companyid = $request->session()->get('companyid', 0);
		$cwhere = '';
		
		if ($request->deptid != '') {
		
			$deptid = implode(",",$request->deptid);
		}

		if($request->attfilter == 'postedAtt' || $request->attfilter == 'unpostedAtt' || $request->attfilter == 'postedAttSummary'){
		
			$cwhere = 'WHERE H.companyid = '.$companyid.'';
		
		}

		if($request->attfilter == 'absenteeList'){
		
			$cwhere = 'WHERE E.companyid = '.$companyid.'';
		}

		if($request->attfilter == 'postedAtt' || $request->attfilter == 'unpostedAtt' || $request->attfilter == 'absenteeList'){
		
			if ($request->employeeid !='' ) {
		
				$cwhere .= ' AND E.id = "'.$request->employeeid .'"';
			}
		}

		if($request->attfilter == 'postedAtt' || $request->attfilter == 'unpostedAtt' || $request->attfilter == 'postedAttSummary'  || $request->attfilter == 'absenteeList'){

			if ($request->locationid !='') {
			
				$cwhere = $cwhere . ' AND E.locationid = '. $request->locationid .'';
			}

			if ($request->deptid !='') {
			
				$cwhere = $cwhere . ' AND E.deptid IN ('. $deptid .')';
			}

			if($request->etypeid !='') {
			
				$cwhere = $cwhere .' AND E.etypeid = '.$request->etypeid.'';
			}
		}

		if ($request->vdate !='' && $request->attfilter == 'postedAtt') {
			
			$cwhere = $cwhere . ' AND H.vdate = "'.$request->vdate .'"';
		}

		if ($request->vdate !='' && $request->attfilter == 'unpostedAtt' ) {
			
			$cwhere = $cwhere . ' AND H.datein LIKE "'.$request->vdate .'%"';
		}

		if($request->attfilter == 'unpostedAttSummary'){
			
			$cwhere = 'WHERE EE.companyid = '.$companyid.'';
			
			if ($request->locationid !='') {
				
				$cwhere = $cwhere . ' AND EE.locationid = '. $request->locationid .'';
			}

			if ($request->deptid !='') {
				
				$cwhere = $cwhere . ' AND EE.deptid IN ('. $deptid .')';
			}

			if($request->etypeid !='') {
				$cwhere = $cwhere .' AND EE.etypeid = '.$request->etypeid.'';
			}
		}

		$request->request->add(['cwhere'   => $cwhere]);
		$request->request->add(['attfilter'   => $request->attfilter]);

		$modelData=$this->getData($request);
		$modelData = $modelData->sort();
		if (!empty($modelData) && ($request->attfilter == 'postedAtt' || $request->attfilter == 'unpostedAtt' || $request->attfilter == 'absenteeList')) {
			foreach ($modelData as  $value) {
				if ($request->attfilter == 'postedAtt') {
					if ($value->attname == "") {
						$value->attname = '-';
					}
				}
				if ($request->attfilter == 'unpostedAtt') {
					if ($value->remarks == "") {
						$value->remarks = '-';
					}
				}
				if ($request->attfilter == 'absenteeList') {
					if ($value->Remarks == "") {
						$value->Remarks = '-';
					}
				}
				// $value->employeename = $value->empcode." - ". $value->employeename;
				if ($request->attfilter == 'postedAtt') {
					$value['starttime'] = str_replace(".",":",$value->starttime);
					$value['tottime'] = str_replace(".",":",$value->tottime);
				}
				// $datein = date("d/m/Y",strtotime($value->datein)); 
				// $value['datein'] = $datein;
				// $dateout = date("d/m/Y",strtotime($value->dateout)); 
				// $value['dateout'] = $dateout;
				// // $date->format('H:i:s A')
				// Log::info($value->datein);
				$timein = date("H:i",strtotime($value->datein)); 
				$value['datein'] = $timein;
				$timeout = date("H:i",strtotime($value->dateout)); 
				$value['dateout'] = $timeout;
			}
		}

		Log::info($modelData);
		return Datatables::of($modelData)
		->make(true);
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

}
