<?php

namespace App\Http\Controllers\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\HrPayroll\Setup\Desg;
use App\Models\HrPayroll\Setup\Shift;
use App\Models\HrPayroll\Setup\Grade;
use App\Models\HrPayroll\Setup\Religion;
use App\Models\HrPayroll\Setup\Location;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\Setup\Bank;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class EmployeeInfoController extends Controller
{
    public $deptModel;
    public $desgModel;
    public $shiftModel;
    public $locationtModel;
    public $religionModel;
    public $gradeModel;
    public $employeeInfoModel;
    public $trialEmployeeModel;
    public $bank;
    public $utilsModel;

    public function __construct() {

        $this->deptModel          = new Dept();
        $this->desgModel          = new Desg();
        $this->shiftModel         = new Shift();
        $this->locationtModel     = new Location();
        $this->gradeModel         = new Grade();
        $this->religionModel      = new Religion();
        $this->employeeInfoModel  = new EmployeeInfo();
        $this->bank               = new Bank();
        $this->utilsModel         = new Utils();
    }

    public function index(Request $request){

        $allDepts      = $this->getDepartments($request);
        $allDesgs      = $this->getDesignations($request);
        $allShifts     = $this->getShifts($request);
        $locations     = $this->getLocations($request);
        $genders       = array('1' => 'Male','2' => 'Female');
        $allgrades     = $this->getGrades($request);
        $allReligions  = $this->getReligions($request);
        $leftstatus    = array('1' => 'ON ROLL','2' => 'RESIGNED','3'=>'TERMINATE');
        $probation     =  array('1' => 'CONTRACT' ,'2' =>'PERMANENT','3' =>'TEMPORARY','4' =>'TRAINEE');
        $eTypes        = array('1' => 'Staff','2' => 'Worker');
        $allBanks      = $this->getBanks($request);
        $employeecode  =  $this->getEmployeeCode($request);
        $user_flag     = $request->session()->get('user_flag');

        $hireTypes     = array('1' => 'New' ,'2' =>'Replacement');
        $weekdays      = array('1' => 'Sunday','2' => 'Monday','3' => 'Tuesday',
            '4' => 'Wednesday','5' => 'Thursday','6' => 'Friday','7' => 'Saturday');
        $bloodgroups   = array('1' => 'A+','2' => 'B+','3' => 'A-','4' => 'B-','5' => 'AB+','6' => 'AB-','7' => 'O+', '8' => 'O-');

        return view('hrpayroll.employee.employee_info',compact('allDepts','allDesgs','allShifts','locations','genders','allgrades','allReligions','hireTypes','leftstatus','probation','weekdays','eTypes','bloodgroups','user_flag','employeecode','allBanks'));
    }

    public function getData($request, $id=0){
        
        return $this->employeeInfoModel->getEmployees($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function fillGrid(Request $request){

        $request->request->add(['isactive' => '-1']); //add request
        $modelData=$this->employeeInfoModel->getEmployees($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT)->toArray();
        $empcode = array_column($modelData, 'empcode');
        
        array_multisort($empcode, SORT_ASC, $modelData);
        
        return Datatables::of($modelData)
        ->addColumn('action', function($data){
            return ' <a class="search-select" id ="searchEditBtn" href="#" data="'.$data['id'].'">Select</a>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function fillForm(Request $request, $id){
        
        $request->request->add(['isactive' => '-1']); //add request
        $modelData=$this->getData($request, $id)[0];
        //set date format
        $modelData['doj']  = date('Y-m-d',strtotime($modelData->doj));
        $modelData['dob']  = date('Y-m-d',strtotime($modelData->dob));
        $modelData['cnicexpiry'] = date('Y-m-d',strtotime($modelData->cnicexpiry));
        $modelData['confirmationdate'] = date('Y-m-d',strtotime($modelData->confirmationdate));
        $modelData['dol'] = date('Y-m-d',strtotime($modelData->dol));
        
        if ($modelData['doj'] == '1970-01-01' || $modelData['doj'] == '1900-01-01') {
        
            $modelData['doj'] = null;
        }
        
        if ($modelData['dob'] == '1970-01-01' || $modelData['dob'] == '1900-01-01') {
        
            $modelData['dob'] = null;
        }
        
        if ($modelData['cnicexpiry'] == '1970-01-01' || $modelData['cnicexpiry'] == '1900-01-01') {
        
            $modelData['cnicexpiry'] = null;
        }
        
        if ($modelData['confirmationdate'] == '1970-01-01' || $modelData['confirmationdate'] == '1900-01-01') {
        
            $modelData['confirmationdate'] = null;
        }
        
        if ($modelData['dol'] == '1970-01-01' || $modelData['dol'] == '1900-01-01') {
        
            $modelData['dol'] = null;
        }
        
        if($modelData['emppic']){
        
            $emppic = $modelData['emppic'];
            $imgsrc = '../'.$emppic;  
            $modelData['emppicImgsrc'] = $imgsrc;
        }



        if($modelData){
        
            return response($modelData);
        
        } else {

            return response(array(
                'error' => 1,
                'data' => 'Employee doesn\'t exists!!',
            ));
        }
    }

    public function save(Request $request){

        if($request->emppic) {
            
            $file = $request->emppic;
            $file_name = $file->getClientOriginalName();
            $destinationPath = public_path('images/employee/'.str_replace(' ', '-', strtolower($request->empcode)));
            $fileSavePath = "images/employee/".str_replace(' ', '-', strtolower($request->empcode));
            $file->move($destinationPath, $file_name);
            $request->emppic = $fileSavePath."/".$file_name;
        
        } else {

            $request->emppic = $request->editpic;
        }
        
        if(!$request->etypeid){
            $request['etypeid'] = 0;
        }
        if(!$request->bankid){
            $request['bankid'] = 0;
        }
        if(!$request->hiretypeid){
            $request['hiretypeid'] = 0;
        }
        if(!$request->jobtypeid){
            $request['jobtypeid'] = 0;
        }
        if(!$request->bloodgroupid){
            $request['bloodgroupid'] = 0;
        }
        if(!$request->offday1id){
            $request['offday1id'] = 0;
        }
        if(!$request->offday2id){
            $request['offday2id'] = 0;
        }
        if(!$request->leftstatusid){
            $request['leftstatusid'] = 0;
        }
        if(!$request->gradeid){
            $request['gradeid'] = 0;
        }
        if(!$request->religionid){
            $request['religionid'] = 0;
        }
        if(!$request['incometax']){
            $request['incometax'] = 0.00;
        }
        if ($request['salary'] <= 0) {
            return redirect()->back()->withInput($request->input())->with('error', 'Salary should be greater than zero.');
        }
        
        if(!(isset($request['id']))){
            $emp_cnicno_validation = EmployeeInfo::where('cnicno', trim($request->cnicno))->first();
    
            if ($emp_cnicno_validation) {
        
                return redirect()->back()->withInput($request->input())->with('error', 'An Employee with same National ID already exist');
            } 

        }
        
        
        
        if ($request->dob == $request->doj) {
        
            return redirect()->back()->withInput($request->input())->with('error', 'Date of Joining and Date of Birth could not be same.');
        }

        if($request->id){
            
            $id=$request->id;
            return $this->employeeInfoModel->updateEmployees($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT); 
        
        } else {

            if($request->trialEmpCode > 0) {

                $this->trialEmployeeModel->deleteTrialEmployees($request->trialEmpCode, $this->utilsModel->CALL_TYPE_DEFAULT);
            }
        
            return $this->employeeInfoModel->createEmployees($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){
     
        return $this->employeeInfoModel->deleteEmployees($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function search(){

        return view('hrpayroll.employee.employee_info_search');
    }

    public function getDepartments(Request $request, $id=0){

        $deptsData = $this->deptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($deptsData);
        return $deptsData;
    }

    public function getDesignations(Request $request, $id=0){

        $desgData = $this->desgModel->getDesgs($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($desgData);
        return $desgData;
    }

    public function getShifts(Request $request, $id=0){

        $shiftData = $this->shiftModel->getShifts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($shiftData);
        return $shiftData;
    }

    public function getLocations(Request $request, $id=0){

        $locationData = $this->locationtModel->getLocations($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($locationData);
        return $locationData;
    }

    public function getGrades(Request $request, $id=0){

        $gradeData = $this->gradeModel->getGrades($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($gradeData); 
        return $gradeData;
    }

    public function getReligions(Request $request, $id=0){

        $religionData = $this->religionModel->getReligions($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($religionData); 
        return $religionData;
    }

    public function getEmployeeCode(Request $request, $id = 0) {

        $employeecode = $this->employeeInfoModel->getEmployeeCode($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        return $employeecode[0]['AUTO_INCREMENT'];
    }

    public function getBanks(Request $request){

        $id = 0;
        $banks =  $this->bank->getBanks($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();  
        natcasesort($banks);  
        return $banks;
    }
}
