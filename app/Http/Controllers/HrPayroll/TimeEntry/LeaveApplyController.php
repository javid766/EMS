<?php

namespace App\Http\Controllers\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\TimeEntry\LeaveApply;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\Setup\AttCode;
use App\Models\Utils;
use App\User;
use DataTables,Auth;
use Log;

class LeaveApplyController extends Controller
{
    
    public $employeeInfoModel;
    public $attLeaveEntryModel;
    public $attAttCodeModel;
    public $utilsModel;

    public function __construct() {
        $this->attAttCodeModel = new AttCode();
        $this->employeeInfoModel = new EmployeeInfo();
        $this->attLeaveEntryModel = new LeaveApply();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){
        
        $leaveType = $this->getLeaveTypes($request);
        $employees = $this->getEmployees($request);
        
        return view('hrpayroll.timeentry.leave_apply',compact('leaveType','employees'));
    }

    public function getData($request, $id=0){
        
        return $this->attLeaveEntryModel->getLeaveEntries($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function fillGrid(Request $request){
        
        $employeeid = Auth::user()->isSuperAdmin() ? 0 : $request->session()->get('employeeid', 0);
        $request->request->add(['employeeid' => $employeeid]); 

        $modelData = $this->getData($request);
        
        foreach ($modelData as $value) {
        
             $value['datein'] = date('d/m/Y', strtotime($value->datein));
             $value['dateout'] = date('d/m/Y', strtotime($value->dateout));
        }
        
        return Datatables::of($modelData)
        ->addColumn('action', function($data){
            return ('<div class="table-actions">
                <button id="editBtn" data="'.$data['id'].'" class="btn btn-info btn-icon"><i class="ik ik-edit"></i></button>

                <button id="deleteBtn" class="btn btn-danger btn-icon"  data="'.$data['id'].'" data-toggle="modal" data-target="#deleteModal"><i class="ik ik-trash-2"></i></button> 
                </div>');
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function fillForm(Request $request, $id){
        
        $request->request->add(['employeeid' => 0]); 
        $modelData = $this->getData($request, $id)[0];

        $modelData['datein'] = date('Y-m-d', strtotime($modelData->datein));
        $modelData['dateout'] = date('Y-m-d', strtotime($modelData->dateout));

        if($modelData){
        
            return response($modelData);
        
        } else {

            return response(array(
                'error' => 1,
                'data' => 'Leave Entry doesn\'t exists!!',
            ));
        }
    }

    public function save(Request $request){
        
        if($request->datein>=$request->dateout){
        
            // return redirect()->back()->with('error','Date to must be greater than Date from');
            return redirect()->back()->withInput($request->input())->with('error', 'Date to must be greater than Date from');
        }
        
        if($request->id){
        
            $id=$request->id;
            return $this->attLeaveEntryModel->updateLeaveEntry($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        
        } else {
        
            return $this->attLeaveEntryModel->createLeaveEntry($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){
        
        return $this->attLeaveEntryModel->deleteLeaveEntry($id, $this->utilsModel->CALL_TYPE_DEFAULT);
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

    public function getLeaveTypes(Request $request, $id=0){
     
        $leaveTypesData = array();
        $leaveTypes = $this->attAttCodeModel->getAttCodes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1);
     
        foreach ($leaveTypes as $value) {
        
             $leaveTypesData[$value['id']] = $value['vname']." (".$value['vcode'] .") ";
        }
        
        natcasesort($leaveTypesData);
        return $leaveTypesData;
    }

    public function indexLeaveApproval(Request $request){

        $leaveType = $this->getLeaveTypes($request);
        $employees = $this->getEmployees($request);
        return view('hrpayroll.timeentry.leave_approval',compact('leaveType','employees'));
    }

    public function fillGridLeaveApproval(Request $request, $id = 0){
        Log::info($request);
        $request->request->add(['employeeid' => 0]); 

        $modelData = $this->attLeaveEntryModel->getLeaveApproval($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        
        foreach ($modelData as $value) {
        
             $value['datein'] = date('d/m/Y', strtotime($value->datein));
             $value['dateout'] = date('d/m/Y', strtotime($value->dateout));
        }
        
        return Datatables::of($modelData)
        ->addColumn('checkboxes', function($data){
            return '
                <input type="checkbox" name="ids[]" value="'.$data->id.'" class="dtcheckbox"/>
            ';                
        })
        ->rawColumns(['checkboxes'])
        ->make(true);
    }

    public function approve(Request $request){

        if ($request->ids != null && count($request->ids) > 0) {

            $inIds = '(' . implode(',', $request->ids) . ')';
            
            $request['inIds'] = $inIds;
            $request['leaveStatus'] = $request->leaveStatus == 'approve' ? 1 : 0 ;
         
            return $this->attLeaveEntryModel->updateApproval($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        
        } else {

            return redirect()->back()->withInput($request->input())->with('success', 'Nothing to update');
        }
    }
}
