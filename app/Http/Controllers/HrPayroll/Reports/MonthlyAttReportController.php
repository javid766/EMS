<?php

namespace App\Http\Controllers\HrPayroll\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utils;
use App\Models\HrPayroll\Setup\Location;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\HrPayroll\Setup\EType;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\Reports\MonthlyAttReport;
use App\User;
use \PDF;
use DataTables,Auth;
use Response;


class MonthlyAttReportController extends Controller
{
	public $utilsModel;
	public $deptModel;
	public $locationtModel;
	public $employeeInfoModel;
	public $attMonthlyReportModel;
	public $attEtypeModel;

	public function __construct() {

		$this->locationtModel = new Location();
		$this->deptModel  = new Dept();
		$this->attEtypeModel = new EType();
		$this->employeeInfoModel = new EmployeeInfo();
		$this->attMonthlyReportModel  = new MonthlyAttReport();
		$this->utilsModel = new Utils();
	}

	public function index(Request $request){

		$locations   = $this->getLocations($request);
		$departments = $this->getDepartments($request);
		$eTypes 	 = $this->getETypes($request);
		$empNames = $this->getEmployees($request);
		return view('hrpayroll.reports.monthy_att_report',compact('locations','departments','eTypes','empNames'));
	}

	public function getData($request, $id=0){
		return $this->attMonthlyReportModel->getAttMonthly($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
	}

	public function fillGrid(Request $request){

		$deptid = '';
		if ($request->deptid != '') {
			$deptid = implode(",",$request->deptid);
		}
		
		$companyid = $request->session()->get('companyid', 0);
		$cwhere = ' WHERE E.companyid = '.$companyid.'';

		if ($request->employeeid !='' ) {
			$cwhere = $cwhere . ' AND E.id = '.$request->employeeid .'';
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


		if ($request->attfilter == 'attcard') {
			if($request->datefrom !='' && $request->dateto !='') {
				$cwhere = $cwhere .' AND H.vdate BETWEEN "'.$request->datefrom.'" AND "'.$request->dateto.'"';
			} elseif ($request->datefrom !='') {
				$cwhere = $cwhere .' AND H.vdate > "'.$request->datefrom.'"';
			} elseif ($request->dateto !='') {
				$cwhere = $cwhere .' AND H.vdate < "'.$request->dateto.'"';
			}
		}

		$request->request->add(['cwhere'   => $cwhere]);
		$request->request->add(['attfilter'   => $request->attfilter]);
		$modelData=$this->getData($request);
		asort($modelData);
		foreach ($modelData as  $value) {
			if ($request->attfilter == 'absenteelist') {
				$value->starttime = str_replace(".",":",$value->starttime);
				$value->doj = date("d/m/Y",strtotime($value->doj)); 
			}
			if ($request->attfilter == 'leavelist') {
				if ($value->remarks == "") {
					$value->remarks = '-';
				}
				$value->datein = date("d/m/Y",strtotime($value->datein));
				$value->dateout = date("d/m/Y",strtotime($value->dateout));  
			}

			if ($request->attfilter == 'attcard'){
				if ($value->attname == "") {
					$value->attname = '-';
				}
				$doj =  date("d-M-Y",strtotime($value->doj)); 
				$value->doj = $doj;
				$value->starttime = str_replace(".",":",$value->starttime);
				$value->tottime = str_replace(".",":",$value->tottime);
				$value->empdetails = "E-Code: ". $value->empcode. " , Name: ". $value->employeename. " , Department: ". $value->department. " , Designation: ". $value->designation ." , DOJ: ".$doj;
			}
			if ($request->attfilter == 'attlogs') {
				if ($value->remarks == "") {
					$value->remarks = '-';
				}

				$value->starttime = str_replace(".",":",$value->starttime);
				$doj = date("d-M-Y",strtotime($value->doj));
				$value->doj = $doj; 
				$value->empdetails = "E-Code: ". $value->empcode. " , Name: ". $value->employeename. " , Department: ". $value->department. " , Designation: ". $value->designation ." , DOJ: ".$doj;
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
	    $request->session()->put('saldatefrom', $request->datefrom);
	    $request->session()->put('saldateto', $request->dateto);
        $request->session()->put('saletypeid', $request->etypeid);
        $request->session()->put('sallocationid', $request->locationid);
        $request->session()->put('saldeptid', $request->deptid);
        $request->session()->put('salattfilter', $request->attfilter);
        
	}


    public function atendanceCard(Request $request , $id=0){
    
	   	$salemployeeid = $request->session()->get('salemployeeid', 0);

		$saldateto      = $request->session()->get('saldateto', 0);
		$saldatefrom      = $request->session()->get('saldatefrom', 0);
		
		$saletypeid    = $request->session()->get('saletypeid', 0);
		$sallocationid = $request->session()->get('sallocationid', 0);
		$saldeptid     = $request->session()->get('saldeptid', 0);
		$salattfilter  = $request->session()->get('salattfilter', 0);
	   	$salcompanyid = $request->session()->get('companyid', 0);
		
		// $companyid = $request->session()->get('companyid', 0);
		$cwhere = ' WHERE E.companyid = '.$salcompanyid.'';

		if ($salemployeeid !='' ) {
			$cwhere = $cwhere . ' AND E.id = '.$salemployeeid .'';
		}
		if ($sallocationid !='') {
			$cwhere = $cwhere . ' AND E.locationid = '. $sallocationid .'';
		}
		if ($saldeptid !='') {
			$cwhere = $cwhere . ' AND E.deptid IN ('. $saldeptid .')';
		}
		if($saletypeid !='') {
			$cwhere = $cwhere .' AND E.etypeid = '.$saletypeid.'';
		}


		if ($salattfilter == 'attcard') {
			if($saldatefrom !='' && $saldateto !='') {
				$cwhere = $cwhere .' AND H.vdate BETWEEN "'.$saldatefrom.'" AND "'.$saldateto.'"';
			} elseif ($saldatefrom !='') {
				$cwhere = $cwhere .' AND H.vdate > "'.$saldatefrom.'"';
			} elseif ($dateto !='') {
				$cwhere = $cwhere .' AND H.vdate < "'.$saldateto.'"';
			}
		}

		$request->request->add(['dateto' => $saldateto]);
		$request->request->add(['datefrom' => $saldatefrom]);

		$request->request->add(['cwhere'   => $cwhere]);
		$request->request->add(['attfilter'   => $salattfilter]);


		$modelData = $this->attMonthlyReportModel->getAttMonthly($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
		
		asort($modelData);

		foreach ($modelData as  $value) {
			if ($request->attfilter == 'absenteelist') {
				$value->starttime = str_replace(".",":",$value->starttime);
				$value->doj = date("d/m/Y",strtotime($value->doj)); 
			}
			if ($request->attfilter == 'leavelist') {
				if ($value->remarks == "") {
					$value->remarks = '-';
				}
				$value->datein = date("d/m/Y",strtotime($value->datein));
				$value->dateout = date("d/m/Y",strtotime($value->dateout));  
			}

			if ($request->attfilter == 'attcard'){
				if ($value->attname == "") {
					$value->attname = '-';
				}
				$doj =  date("d-M-Y",strtotime($value->doj)); 
				$value->doj = $doj;
				$value->starttime = str_replace(".",":",$value->starttime);
				$value->tottime = str_replace(".",":",$value->tottime);
				$value->empdetails = "E-Code: ". $value->empcode. " , Name: ". $value->employeename. " , Department: ". $value->department. " , Designation: ". $value->designation ." , DOJ: ".$doj;
			}
			if ($request->attfilter == 'attlogs') {
				if ($value->remarks == "") {
					$value->remarks = '-';
				}

				$value->starttime = str_replace(".",":",$value->starttime);
				$doj = date("d-M-Y",strtotime($value->doj));
				$value->doj = $doj; 
				$value->empdetails = "E-Code: ". $value->empcode. " , Name: ". $value->employeename. " , Department: ". $value->department. " , Designation: ". $value->designation ." , DOJ: ".$doj;
			}
			
		}

		$employee_wise_att_data = $this->employeeGrouping($modelData);
		
		$attData = ['attData' => $employee_wise_att_data, 'datefrom' => $saldatefrom, 'dateto' => $saldatefrom ];

        $pdf = PDF::loadView('hrpayroll.reports.attendance_card_pdf', $attData)->setPaper('ledger', 'landscape');

        $filename = 'attendance_card.pdf';
        $pdf->save(base_path('AttendanceCard/'.$filename));   
        $path = base_path('AttendanceCard/'.$filename);
        
        return Response::make(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"'
        ]);

		

		//return Datatables::of($modelData)->make(true);

	}

	function employeeGrouping($salSheetData){
	
		$my_data = array();
		foreach ($salSheetData as $key => $value) {
			
			$current_department = $value->empdetails;
			$my_data[$current_department][] = $value;
		}
		
		return $my_data;
	}

}
