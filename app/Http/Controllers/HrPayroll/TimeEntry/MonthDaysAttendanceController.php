<?php

namespace App\Http\Controllers\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\TimeEntry\MonthDaysAttendance;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\Utils;
use App\User;
use DataTables, Auth;

class MonthDaysAttendanceController extends Controller
{
    public $employeeInfoModel;
    public $monthDaysAttModel;
    public $DeptModel;
    public $utilsModel;

    public function __construct() {
        $this->monthDaysAttModel = new MonthDaysAttendance();
        $this->employeeInfoModel = new EmployeeInfo();
        $this->DeptModel = new Dept();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){

        $empNames = $this->getEmployees($request);
        $allDepts = $this->getDepartments($request);
        return view('hrpayroll.timeentry.month_days_attendance',compact('allDepts','empNames'));
    }

    public function getData($request, $id=0){
        return $this->monthDaysAttModel->getMonthDaysAttendance($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

    }

    public function search(Request $request){
        $employeeid = (int)$request->get('employeeid');
        $deptid  = (int)$request->get('deptid');
        $datein  = $request->get('datein');
        //last date of the month
        $last_date_find = strtotime(date("Y-m-d", strtotime($datein)) . ", last day of this month");
        $last_date = date("Y-m-d",$last_date_find);
        $request->request->add(['datein' => $datein]);
        $request->request->add(['dateout' => $last_date]);
        $request['employeeid'] = $employeeid;
        $data = $this->getData($request);

        /*Frontend filter */
        // if(($deptid)){  
        //     foreach($data as $key => $entry){             
        //         if($entry->deptid != $deptid){ 
        //             unset($data[$key]);
        //         } 
        //     }
        // }
        /*Frontend sort */
        //$data = $data->sortBy('empcode');


        $userid = Auth::id();
            $datein = date('Y-m-d', strtotime($request->datein));
        // $dateout = date('Y-m-d 00:00:00', strtotime($request->datein . ' +1 day'));
            $dateout = $last_date;


        return redirect()->back()->withInput($request->input())
            ->with([
                'data' => $data,
                'q_empid' => $request['employeeid'],
                'q_datein' => $datein,
                'q_dateout' => $dateout,
            ]);
    }

    public function save(Request $request){
        
        $empGridData = $request->all();   
        $datein = $empGridData['datein']; 
        $result = '';
        $month = date('m', strtotime($datein)); //month
        $year = date('Y', strtotime($datein)); //year
        $dayscount=cal_days_in_month(CAL_GREGORIAN, $month,$year);

        if ($request->get('data') == null) {
        
            return redirect()->back()->withInput($request->input())->with('success' , "Nothing to change.");
        
        } else {
        
            foreach ($empGridData['data'] as  $value) {
                $id = $value['id'];
            
                if (isset($value["changedattdays"])){
                    
                    if($value['attdays'] !== "0"){
                       
                        if ($value['attdays'] > $dayscount) {
                        
                            return redirect()->back()->withInput($request->input())->with('error' , "Days can't be greater than ". $dayscount);
                        }
                        
                        $_request = $request;
                        $_request->request->add($value);
                        
                        if($id <=0){
                        
                            $result = $this->monthDaysAttModel->createMonthDaysAttendance($_request, $this->utilsModel->CALL_TYPE_DEFAULT);
                        
                        } elseif ($id >0) {
                        
                            $result = $this->monthDaysAttModel->updateMonthDaysAttendance($_request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
                        }
                    }
                }
            }

            if ($result) {
                return $result;
            }
            else{
                return redirect()->back()->withInput($request->input())->with('success' , "Nothing to change."); 
            }                       
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
}
