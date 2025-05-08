<?php

namespace App\Http\Controllers\HrPayroll\Posting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\Utils;
use App\User;
use DataTables,Auth;
use App\Models\HrPayroll\Posting\SalaryPosting;

class SalaryPostingController extends Controller
{

    public $utilsModel;
    public $employeeInfoModel;
    public $salaryPostingModel;

    public function __construct() {
        
        $this->utilsModel = new Utils();
        $this->employeeInfoModel = new EmployeeInfo();
        $this->salaryPostingModel = new SalaryPosting();
    }

    public function index(Request $request){
        
        $empNames = $this->getEmployees($request);
        return view('hrpayroll.posting.salary_posting',compact('empNames'));
    }

    public function save(Request $request){

        $message = $this->salaryPostingModel->salaryPosting($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        $response = json_decode($message);

        if(count($response) > 0 && ($response[0]->message??null) == null){
            
            return redirect()->back()->withInput($request->input())->with('success' , 'Salary Posted Successfully for '. date('M Y', strtotime($request->vdate)));

        } else {

          return redirect()->back()->withInput($request->input())->with('error' , $response[0]->message);
        }    
    }

    public function delete($id){
        return $this->attHolidayModel->deleteHoliday($id, $this->utilsModel->CALL_TYPE_DEFAULT);
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
