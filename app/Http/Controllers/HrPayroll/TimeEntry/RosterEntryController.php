<?php

namespace App\Http\Controllers\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\TimeEntry\RosterEntry;
use App\Models\HrPayroll\Setup\RosterShift;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\Utils;
use App\User;
use DataTables, Auth;

class RosterEntryController extends Controller
{
    public $employeeInfoModel;
    public $rosterEntryModel;
    public $attRosterShiftModel;
    public $DeptModel;
    public $utilsModel;

    public function __construct() {
        $this->rosterEntryModel = new RosterEntry();
        $this->attRosterShiftModel = new RosterShift();
        $this->employeeInfoModel = new EmployeeInfo();
        $this->DeptModel = new Dept();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){
        $empNames = $this->getEmployees($request);
        $allDepts = $this->getDepartments($request);
        return view('hrpayroll.timeentry.roster_entry',compact('allDepts','empNames'));
    }

    public function getData($request, $id=0){
        return $this->rosterEntryModel->getRosterEntries($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

    }

    public function search(Request $request){
        $employeeid = (int)$request->get('employeeid');
        $deptid  = (int)$request->get('deptid');
        $datein  = $request->get('vdate');
        $dateout = date('Y-m-31',strtotime($datein));

        $request['employeeid'] = $employeeid;
        $request['datein'] = $datein;    
        $request->request->add(['dateout' => $dateout]);
        $request->session()->put('datein', $datein);
        $data = $this->getData($request);

        /*frontend filer */
        // if(($deptid)){  
        //     foreach($data as $key => $entry){             
        //         if($entry->deptid != $deptid){ 
        //             unset($data[$key]);
        //         } 
        //     }
        // }
        /* frontend sorting*/
        //$data = $data->sortBy('empcode');
        return redirect()->back()->withInput($request->input())->with(['data' => $data]);
    }
    public function validateRosterShiftEntries(Request $request)
    {
        $rshifts = $this->getShiftRosters($request)->toArray();
        $res = in_array(strtolower($request->rshift), array_map('strtolower', $rshifts));
        if ($res == 0) {
            return 0;
        } 
        else{
            return 1;
        }
    }
    public function validateShifts($request, $empGridData){
        $row = 1;
        $rshifts = $this->getShiftRosters($request)->toArray();
        if (array_key_exists("data",$empGridData)){
            foreach ($empGridData['data'] as  $value) {
                if($value['d01'] != ''){
                    $res = in_array(strtolower($value['d01']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 1');
                    } 
                }
                if($value['d02'] != ''){
                    $res = in_array(strtolower($value['d02']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 2');
                    } 
                }
                if($value['d03'] != ''){
                    $res = in_array(strtolower($value['d03']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 3');
                    } 
                }
                if($value['d04'] != ''){
                    $res = in_array(strtolower($value['d04']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 4');
                    } 
                }
                if($value['d05'] != ''){
                    $res = in_array(strtolower($value['d05']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 5');
                    } 
                }
                if($value['d06'] != ''){
                    $res = in_array(strtolower($value['d06']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 6');
                    } 
                }
                if($value['d07'] != ''){
                    $res = in_array(strtolower($value['d07']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 7');
                    } 
                }
                if($value['d08'] != ''){
                    $res = in_array(strtolower($value['d08']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 8');
                    } 
                }
                if($value['d09'] != ''){
                    $res = in_array(strtolower($value['d09']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 9');
                    } 
                }
                if($value['d10'] != ''){
                    $res = in_array(strtolower($value['d10']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 10');
                    } 
                }
                if($value['d11'] != ''){
                    $res = in_array(strtolower($value['d11']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 11');
                    } 
                }
                if($value['d12'] != ''){
                    $res = in_array(strtolower($value['d12']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 12');
                    } 
                }
                if($value['d13'] != ''){
                    $res = in_array(strtolower($value['d13']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 13');
                    } 
                }
                if($value['d14'] != ''){
                    $res = in_array(strtolower($value['d14']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 14');
                    } 
                }
                if($value['d15'] != ''){
                    $res = in_array(strtolower($value['d15']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 15');
                    } 
                }
                if($value['d16'] != ''){
                    $res = in_array(strtolower($value['d16']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 16');
                    } 
                }
                if($value['d17'] != ''){
                    $res = in_array(strtolower($value['d17']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 17');
                    } 
                }
                if($value['d18'] != ''){
                    $res = in_array(strtolower($value['d18']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 18');
                    } 
                }
                if($value['d19'] != ''){
                    $res = in_array(strtolower($value['d19']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 19');
                    } 
                }
                if($value['d20'] != ''){
                    $res = in_array(strtolower($value['d20']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 20');
                    } 
                }
                if($value['d21'] != ''){
                    $res = in_array(strtolower($value['d21']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 21');
                    } 
                }
                if($value['d22'] != ''){
                    $res = in_array(strtolower($value['d22']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 22');
                    } 
                }
                if($value['d23'] != ''){
                    $res = in_array(strtolower($value['d23']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 23');
                    } 
                }
                if($value['d24'] != ''){
                    $res = in_array(strtolower($value['d24']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 24');
                    } 
                }
                if($value['d25'] != ''){
                    $res = in_array(strtolower($value['d25']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 25');
                    } 
                }
                if($value['d26'] != ''){
                    $res = in_array(strtolower($value['d26']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 26');
                    } 
                }
                if($value['d27'] != ''){
                    $res = in_array(strtolower($value['d27']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 27');
                    } 
                }
                if($value['d28'] != ''){
                    $res = in_array(strtolower($value['d28']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 28');
                    } 
                }
                if($value['d29'] != ''){
                    $res = in_array(strtolower($value['d29']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 29');
                    } 
                }
                if($value['d30'] != ''){
                    $res = in_array(strtolower($value['d30']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 30');
                    } 
                }
                if($value['d31'] != ''){
                    $res = in_array(strtolower($value['d31']), array_map('strtolower', $rshifts));
                    if ($res == 0) {
                        return redirect()->back()->withInput($request->input())->with('error', 'Wrong Roster Shift Entry at row '. $row . ' and column 31');
                    } 
                }
                $row++;
            }
        }
    }

    public function save(Request $request){
        $empGridData = $request->all();  
        $datein = $empGridData['vdate']; 
        $month = date('m', strtotime($datein)); //month
        $year = date('Y', strtotime($datein)); //year
        $dayscount=cal_days_in_month(CAL_GREGORIAN, $month,$year);

        $result = '';
        $res = $this->validateShifts($request, $empGridData);
        if ($request->session()->get('error')) {
            return $res;
        }
        else{
            if ($request->get('data') == null) {
                return redirect()->back()->withInput($request->input())->with(['error' => 'Please Fill  the Roster Entry Form !!']); 
            }
            else{
                foreach ($empGridData['data'] as  $value) {
                    $id = $value['id'];
                    if($value['d01'] || $value['d02'] || $value['d03'] || $value['d04'] || $value['d05'] || $value['d06'] || $value['d07'] || $value['d08'] || $value['d09'] || $value['d10'] || $value['d11'] || $value['d12'] || $value['d13'] || $value['d14'] || $value['d15'] || $value['d16']  || $value['d17']  || $value['d18'] || $value['d19'] || $value['d20'] || $value['d21'] || $value['d22'] || $value['d23'] || $value['d24'] || $value['d25'] || $value['d26'] || $value['d27'] || $value['d28'] || $value['d29'] ||$value['d30'] || $value['d31']  ){
                        if ($dayscount == 28) { //Feb
                            $value['d29'] = '';
                            $value['d30'] = '';
                            $value['d31'] = '';
                        }
                        if ($dayscount == 30) {
                            $value['d31'] = '';
                        }
                        $_request = $request;
                        $_request->request->add($value);
                        if ($id > 0) {
                            $result = $this->rosterEntryModel->updateRosterEntry($_request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
                        }
                        else{
                            $result = $this->rosterEntryModel->createRosterEntry($_request, $this->utilsModel->CALL_TYPE_DEFAULT);
                        }
                    }
                }
                if ($result) {
                    return $result;
                }
                else{
                    return redirect()->back()->withInput($request->input())->with(['error' => 'Please Fill the Roster Entry Form !!']); 
                } 
            }
        }
    }

    public function getDepartments(Request $request, $id=0){
        $deptsData = $this->DeptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->pluck('vname','id')->toArray();
        natcasesort($deptsData);
        return $deptsData;
    }

    public function getEmployees(Request $request, $id=0){
        $request->request->add(['isactive' => '1']); //add request
        $employeeInfoData = array();
        $employeeInfo = $this->employeeInfoModel->getEmployees($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isshiftemployee','=',1);
        foreach ($employeeInfo as $value) {
         $employeeInfoData[$value['id']] = $value['employeename']." ".$value['fathername']." (".$value['empcode'] .") ";
     }
     natcasesort($employeeInfoData);
     return $employeeInfoData;
 }
 public function getShiftRosters($request, $id=0){

    return $this->attRosterShiftModel->getRosterShifts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->pluck('vcode', 'id'); 
}

}
