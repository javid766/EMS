<?php

namespace App\Http\Controllers\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\TimeEntry\ChangeAttendence;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\Setup\EType;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class ChangeAttendenceController extends Controller
{
    public $changeAttendenceModel;
    public $employeeInfoModel;
    public $DeptModel;
    public $utilsModel;

    public function __construct() {
        $this->changeAttendenceModel = new ChangeAttendence();
        $this->employeeInfoModel = new EmployeeInfo();
        $this->eTypeModel = new EType();
        $this->DeptModel = new Dept();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){
        
        $allDepts = $this->getDepartments($request);
        $empNames = $this->getEmployees($request);
        $eTypes = $this->getETypes($request);
        
        return view('hrpayroll.timeentry.change_attendence',compact('allDepts','empNames', 'eTypes'));
    }

    public function getData($request, $id=0){
    
        return $this->changeAttendenceModel->getEmpChangeAttendence($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function search(Request $request){

        $empid = (int)$request->get('empid');
        $deptid  = (int)$request->get('deptid');
        $etypeid  = (int)$request->get('etypeid');
        $vdate  = $request->get('vdate');
        $request->request->add(['vdate' => $vdate]);

        if ($empid > 0) {
        
            $request['employeeid'] = $empid;
        
        } else {           

            $request['employeeid'] = 0; 
        }

        $data = $this->getData($request);
        
        // foreach($data as $key => $entry){  
        
        //     if ($deptid) {
        
        //         if($entry->deptid != $deptid){ 
        
        //             unset($data[$key]);
        //         }
        //     } 
        
        //     if ($etypeid > 0) {

        //         if($entry->etypeid != $etypeid){ 
        
        //             unset($data[$key]);
        //         } 
        //     }           
        // }

        // $allDepts = $this->getDepartments($request);
        // $empNames = $this->getEmployees($request);
        // $eTypes = $this->getETypes($request);
        // $userid = Auth::id();
    
        $datein = date('Y-m-d 00:00:00', strtotime($request->vdate));
        $dateout = date('Y-m-d 00:00:00', strtotime($request->vdate . ' +1 day'));
    
        return redirect()->back()->withInput($request->input())
        ->with([
            'data' => $data,
            'q_empid' => $empid,
            'q_datein' => $datein,
            'q_dateout' => $dateout,
            'q_deptid' => $deptid,
            'q_etypeid' => $etypeid,
        ]);
    }

    public function save(Request $request){

        $empGridData = $request->all();  
        $result = '';  
        
        if ($request->get('data') == null) {
        
            return redirect()->back()->withInput($request->input())->with('success' , "Nothing to change");
        
        } else {

            foreach ($empGridData['data'] as  $value) {
        
                $id = $value['id'];
                $_request = $request;
                $_request->request->add($value);
                $datein=$request->datein;
                $dateout=$request->dateout;

                if ($datein == null && $dateout == null) {

                    $_datein = $_dateout = "1900-01-01 00:00:00";

                } else {
                    
                    $message = '';
                    $date = date('Y-m-d', strtotime($request->vdate));
                    $_datein = $date .' '. $datein . ':00';
                    $_dateout = $date .' '. $dateout . ':00';

                    if (!$this->isvalidDateInOut($_datein, $_dateout, $message)) {

                       return redirect()->back()->withInput($request->input())->with('error' , $message); 
                    }
                }

                $_request->request->add([
                    'datein' => $_datein,
                    'dateout' => $_dateout,
                ]);

                // if ($id >0) {

                    $result = $this->changeAttendenceModel->updateAttendanceEmployee($_request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

                // } elseif ($id <= 0 ){
                    
                    // $result = $this->changeAttendenceModel->createAttendanceEmployee($_request, $this->utilsModel->CALL_TYPE_DEFAULT);
                // }
            }

            return $result;
        }
    }

    public function getDepartments(Request $request, $id=0){
        
        $deptsData = $this->DeptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        
        natcasesort($deptsData);
        
        return $deptsData;
    }

    public function getEmployees(Request $request, $id=0){
        
        $request->request->add(['isactive' => '1']); //add request
        $employeeInfoData = array();
        $employeeInfo = $this->employeeInfoModel->getEmployees($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        
        foreach ($employeeInfo as $value) {
        
           $employeeInfoData[$value['id']] = $value['employeename']." ".$value['fathername']." (".$value['empcode'] .") ";
        }
       
        natcasesort($employeeInfoData);
        return $employeeInfoData;
   }

    public function getETypes(Request $request, $id=0){

        $eTypesData = $this->eTypeModel->getETypes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        
        natcasesort($eTypesData); 
        return $eTypesData;
    }

    public function isvalidDateInOut(&$datein, &$dateout, &$message) {

        if (date('H:i', strtotime($datein)) == "00:00" && date('H:i', strtotime($dateout)) != "00:00") {
            
            $message = "Please Correct Out-Time of Employee";
            return false;
        }

        if ($datein > $dateout) {

            $dateout = date('Y-m-d H:i:s', strtotime($dateout . ' +1 day'));
        }

        if ($this->differenceInHours($dateout, $datein) >= 24) {

            $message = "Employee In/Out is More than 24hrs";
            return false;
        }

        return true;
    }

    public function differenceInHours($startdate,$enddate){
        
        $starttimestamp = strtotime($startdate);
        $endtimestamp = strtotime($enddate);
        $difference = abs($endtimestamp - $starttimestamp)/3600;
        return $difference;
    }
}
