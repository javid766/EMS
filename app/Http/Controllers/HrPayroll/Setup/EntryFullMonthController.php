<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\LeaveBalance;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class EntryFullMonthController extends Controller
{
    public $attLeaveBalanceModel;
    public $utilsModel;

    public function __construct() {

        $this->attLeaveBalanceModel = new LeaveBalance();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){

         $leaveType = array('AL (Annual Leave)'  => 'AL (Annual Leave)',
           'CL (Casual Leave)'      => 'CL (Casual Leave)',
           'CPL (CPL)'              => 'CPL (CPL)',
           'HL (Half Day Leave)'    => 'HL (Half Day Leave)',
           'ML (Maternity Leave)'   => 'ML (Maternity Leave)',
           'SHL (Short Leave)'      => 'SHL (Short Leave)',
           'SL (Sick Leave)'        => 'SL (Sick Leave)',       
           'SPL (Special Leave)'    => 'SPL (Special Leave)',
           'TL (Paternity Leave)'   => 'TL (Paternity Leave)',  
           'WO (Leave Without Pay)' => 'WO (Leave Without Pay)'
        );
        //$attGroupsData = $this->getAttGroupsData($request);
        return view('hrpayroll.employee.leave_apply',compact('leaveType'));
    }

    public function getData($request, $id=0){

        return $this->attLeaveBalanceModel->getLeaveBalances($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

    }

    public function fillGrid(Request $request){

        $modelData=$this->getData($request);
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

        $modelData=$this->getData($request, $id)[0];
        $datein = strtotime($modelData->datein);
        $dateout = strtotime($modelData->dateout);
        $datein = date('Y-m-d',$datein); 
        $dateout = date('Y-m-d',$dateout);
        $modelData['datein']=$datein;
        $modelData['dateout']=$dateout;
        if($modelData){
            return response($modelData);
        }
        else{
            return response(array(
                'error' => 1,
                'data' => 'Leave Balance doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){

        $leaveBalance = LeaveBalance::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();
        
        if ($leaveBalance) {

            return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
        }

        if($request->id){

            $id=$request->id;

            return $this->attLeaveBalanceModel->updateLeaveBalance($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

        } else {

            return $this->attLeaveBalanceModel->createLeaveBalance($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){

        return $this->attLeaveBalanceModel->deleteLeaveBalance($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

}
