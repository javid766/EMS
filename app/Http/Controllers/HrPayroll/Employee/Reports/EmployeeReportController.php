<?php

namespace App\Http\Controllers\HrPayroll\Employee\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\HrPayroll\Setup\Desg;
use App\Models\HrPayroll\Setup\Religion;
use App\Models\HrPayroll\Setup\EType;
use App\Models\HrPayroll\Setup\Gender;
use App\Models\HrPayroll\Setup\ProbationStatus;
use App\Models\HrPayroll\Setup\LeftStatus;
use App\Models\HrPayroll\Employee\Reports\EmployeeReport;
use App\Models\HrPayroll\Setup\Location;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class EmployeeReportController extends Controller
{
    public $deptModel;
    public $desgModel;
    public $religionModel;
    public $locationModel;
    public $eTypeModel;
    public $genderModel;
    public $probationModel;
    public $leftStatusModel;
    public $attHolidayModel;
    public $utilsModel;
    public $empReportModel;

    public function __construct() {
        $this->deptModel = new Dept();
        $this->desgModel = new Desg();
        $this->religionModel = new Religion();
        $this->locationModel = new Location();
        $this->eTypeModel = new EType();
        $this->genderModel = new Gender();
        $this->probationModel = new ProbationStatus();
        $this->leftStatusModel = new LeftStatus();
        $this->empReportModel = new EmployeeReport(); 
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){
        $bool = array('1'=>'Yes','0'=>'No' );
        $allDepts = $this->getDepartments($request);
        $allDesgs  = $this->getDesignations($request);
        $allReligions  = $this->getReligions($request);
        $allLocations  = $this->getLocations($request);
        $allEtypes  = $this->getETypes($request);
        $allGenders = $this->getGenders($request);
        $allProbationStatus = $this->getProbationStatus($request);
        $allLeftStatus = $this->getLeftStatus($request);
        return view('hrpayroll.employee.reports.employee_report',compact('allDepts','allDesgs','allReligions','allLocations','allEtypes','allGenders','allProbationStatus','bool','allLeftStatus'));
    }

    public function getData($request, $id=0){
       return $this->empReportModel->getEmpData($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
   }

   public function fillGrid(Request $request){
    $companyid = $request->session()->get('companyid', 0);
    $cwhere = ' WHERE H.companyid = '.$companyid.'';
    if ($request->etypeid !='') {
        $cwhere = $cwhere .' AND H.etypeid =  '. $request->etypeid .'';
    }
    if ($request->locationid !='') {
        $cwhere = $cwhere . ' AND H.locationid = '. $request->locationid .'';
    }
    if ($request->deptid !='') {
        $cwhere = $cwhere . ' AND H.deptid = '. $request->deptid .'';
    }
    if ($request->desgid !='') {
        $cwhere = $cwhere . ' AND H.desgid = '. $request->desgid .'';
    }
    if ($request->genderid !='') {
        $cwhere = $cwhere . ' AND H.genderid = '. $request->genderid .'';
    }
    if ($request->religionid !='') {
        $cwhere = $cwhere . ' AND H.religionid = '. $request->religionid .'';
    }
    if ($request->jobtypeid !='') {
        $cwhere = $cwhere . ' AND H.jobtypeid = '. $request->jobtypeid .'';
    }
    if ($request->ishod !='') {
        $cwhere = $cwhere . ' AND H.ishod = '. $request->ishod .'';
    }
    if ($request->isshiftemployee !='') {
        $cwhere = $cwhere . ' AND H.isshiftemployee = '. $request->isshiftemployee .'';
    }
    if ($request->haveot !='') {
        $cwhere = $cwhere . ' AND H.haveot = '. $request->haveot .'';
    }
    if ($request->issalarytobank !='') {
        $cwhere = $cwhere . ' AND H.issalarytobank = '. $request->issalarytobank .'';
    }
    if ($request->leftstatusid !='') {
        $cwhere = $cwhere . ' AND H.leftstatusid = '. $request->leftstatusid .'';
    }
    if ($request->dojfrom !='' && $request->dojto !='') {
        $cwhere = $cwhere . ' AND H.doj BETWEEN "'. $request->dojfrom .'" AND "'. $request->dojto .'"';
    }
    if ($request->dojfrom !='' && $request->dojto == '') {
        $cwhere = $cwhere . ' AND H.doj = "'. $request->dojfrom .'" ';
    }
    if ($request->dojfrom =='' && $request->dojto !='') {
        $cwhere = $cwhere . ' AND H.doj = "'. $request->dojto .'" ';
    }
    if ($request->dolfrom !='' && $request->dolto !='') {
        $cwhere = $cwhere . ' AND H.dol BETWEEN "'. $request->dolfrom .'" AND "'. $request->dolto .'"';
    }
    if ($request->dolfrom !='' && $request->dolto =='') {
        $cwhere = $cwhere . ' AND H.dol = "'. $request->dolfrom .'" ';
    }
    if ($request->dolfrom =='' && $request->dolto !='') {
        $cwhere = $cwhere . ' AND H.dol = "'. $request->dolto .'" ';
    }
    if ($request->dobfrom !='' && $request->dobto !='') {
        $cwhere = $cwhere . ' AND H.dob BETWEEN "'. $request->dobfrom .'" AND "'. $request->dobto .'"';
    }
    if ($request->dobfrom !='' && $request->dobto =='') {
        $cwhere = $cwhere . ' AND H.dob = "'. $request->dobfrom .'" ';
    }
    if ($request->dobfrom =='' && $request->dolto !='') {
        $cwhere = $cwhere . ' AND H.dob = "'. $request->dobto .'" ';
    }
    if ($request->salaryfrom !='' && $request->salaryto !='') {
        $cwhere = $cwhere . ' AND H.salary BETWEEN '. $request->salaryfrom .' AND '. $request->salaryto ;
    }
    if ($request->salaryfrom !='' && $request->salaryto =='') {
        $cwhere = $cwhere . ' AND H.salary = '. $request->salaryfrom .' ';
    }
    if ($request->salaryfrom =='' && $request->salaryto !='') {
        $cwhere = $cwhere . ' AND H.salary = '. $request->salaryto .' ';
    }
    $request->request->add(['cwhere'   => $cwhere]);
    $request->request->add(['attfilter'   => $request->attfilter]);
    $modelData=$this->getData($request);
    foreach ($modelData as $value) {
       if($request->attfilter == 'empdetailreport' || $request->attfilter == 'empcardreport') 
       {
           $value->employeename = $value->empcode." - ". $value->employeename. " ". $value->fathername;
           $dob = date("d/m/Y",strtotime($value->dob)); 
           $value->dob = $dob;
           $doj = date("d/m/Y",strtotime($value->doj)); 
           $value->doj = $doj;
           $value->timein = str_replace(array('.'), ':', $value->timein);
           $value->timeout = str_replace(array('.'), ':', $value->timeout);
       }
       else{
           $value->employeename = $value->employeename. " ". $value->fathername;
       }

   }
   return Datatables::of($modelData)
   ->make(true);
}

public function getDepartments(Request $request, $id=0){

    $departments = $this->deptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($departments); 
       return $departments;
}

public function getDesignations(Request $request, $id=0){

    $designations =  $this->desgModel->getDesgs($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($designations); 
       return $designations;
}

public function getReligions(Request $request, $id=0){

    $religion = $this->religionModel->getReligions($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($religion); 
       return $religion;
}

public function getLocations(Request $request, $id=0){

    $locations =  $this->locationModel->getLocations($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($locations); 
       return $locations;
}

public function getETypes(Request $request, $id=0){

    $etypes = $this->eTypeModel->getETypes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($etypes); 
       return $etypes;
}
public function getGenders(Request $request, $id=0){

    $genders =  $this->genderModel->getGenders($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($genders); 
       return $genders;
}
public function getProbationStatus(Request $request, $id=0){

    $probationstatus = $this->probationModel->getProbationStatuses($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($probationstatus); 
       return $probationstatus;
}

public function getLeftStatus(Request $request, $id=0){

    $leftstatus = $this->leftStatusModel->getLeftStatuses($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($leftstatus); 
       return $leftstatus;
}

}
