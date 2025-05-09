<?php

namespace App\Http\Controllers\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\HrPayroll\Setup\Shift;
use App\Models\HrPayroll\Employee\TrialEmployee;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class TrialEmployeeEntryController extends Controller
{
    public $DeptModel;
    public $ShiftModel;
    public $trialEmpModel;
    public $utilsModel;

    public function __construct() {
        $this->DeptModel = new Dept();
        $this->ShiftModel = new Shift(); 
        $this->trialEmpModel = new TrialEmployee();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){
        $allDepts   = $this->getDepartments($request);
        $allShifts  = $this->getShifts($request);
        $hireTypes  = array('1' => 'New', '2' =>'Replacement',);
        $closingstatus = array('1' =>'Working' );
        $employeecode  =  $this->getEmployeeCode($request);
        return view('hrpayroll.employee.trial_employee_entry',compact('employeecode','allDepts','allShifts','hireTypes','closingstatus'));
    }

    public function getData($request, $id=0){
        $request->request->add(['isactive' => '-1']); //add request
        return $this->trialEmpModel->getTrialEmployees($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);  
    }

    public function fillGrid(Request $request){
        $request->request->add(['isactive' => '-1']); //add request
        $modelData=$this->trialEmpModel->getTrialEmployees($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT)->toArray();
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
        $modelData['closingdate'] = date('Y-m-d',strtotime($modelData->closingdate));
        if ($modelData['doj'] == '1970-01-01') {
            $modelData['doj'] = null;
        }
        if ($modelData['dob'] == '1970-01-01') {
            $modelData['dob'] = null;
        }
        if ($modelData['closingdate'] == '1970-01-01') {
            $modelData['closingdate'] = null;
        }
        if($modelData['emppic']){
            $emppic = $modelData['emppic'];
            $imgsrc = '../'.$emppic;  
            $modelData['emppicImgsrc'] = $imgsrc;
        }

         if($modelData){
            return response($modelData);
            }
         else{
                return response(array(
                    'error' => 1,
                    'data' => 'Employee doesn\'t exists!!',
                ));
            }

    }

    public function save(Request $request){
        
        if(!$request->hiretypeid){
            $request['hiretypeid'] = 0;
        }
        if(!$request->closingstatus){
            $request['closingstatus'] = 0;
        }
        if($request->emppic) {
            $file = $request->emppic;
            $file_name = $file->getClientOriginalName();
            $destinationPath = public_path('images/trial_employees/'.str_replace(' ', '-', strtolower($request->empcode)));
            $fileSavePath = "images/trial_employees/".str_replace(' ', '-', strtolower($request->empcode));
            $file->move($destinationPath, $file_name);
            $request->emppic = $fileSavePath."/".$file_name;
        } 
        else{
            $request->emppic = $request->editpic;
        }
        if(!$request->hiretypeid){
            $request['hiretypeid'] = 0;
        }
        if(!$request->shiftid){
            $request['shiftid'] = 0;
        }
        if(!$request->deptid){
            $request['deptid'] = 0;
        }
         if(!$request->closingstatus){
            $request['closingstatus'] = 0;
        }
        if($request->id == 0){
            $emp_cnicno_validation = EmployeeInfo::where('cnicno', trim($request->cnicno))->first();
            if ($emp_cnicno_validation) {
                return redirect()->back()->withInput($request->input())->with('error', 'An Employee with same National ID already exist');
            }
        } 
        
        if (strlen($request->cnicno) > 15) {
            return redirect()->back()->withInput($request->input())->with('error', 'National ID must be in xxxxx-xxxxxxx-x');
        }
        if ($request->dob == $request->doj) {
            return redirect()->back()->withInput($request->input())->with('error', 'Date of Joining and Date of Birth could not be same.');
        }
        if($request->id){
            $id=$request->id;
            return $this->trialEmpModel->updateTrialEmployees($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT); 
        } 
        else {
            return $this->trialEmpModel->createTrialEmployees($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function search(){
     return view('hrpayroll.employee.trial_employee_search');
   }

    public function getDepartments(Request $request, $id=0){  
        $departments = $this->DeptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($departments); 
       return $departments;
    }

    public function getShifts(Request $request, $id=0){
        $shifts = $this->ShiftModel->getShifts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($shifts); 
       return $shifts;
    }

    public function getEmployeeCode(Request $request, $id = 0) {
        $employeecode = $this->trialEmpModel->getEmployeeCode($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        return $employeecode[0]['AUTO_INCREMENT'];
    }

}
