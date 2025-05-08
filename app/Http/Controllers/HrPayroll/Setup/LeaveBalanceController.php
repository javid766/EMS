<?php

namespace App\Http\Controllers\HrPayroll\Setup;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\LeaveBalance;
use App\Models\HrPayroll\Setup\AttGroup;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class LeaveBalanceController extends Controller
{
    public $attLeaveBalanceModel;
    public $attAttGroupModel;
    public $utilsModel;

    public function __construct() {
        $this->attLeaveBalanceModel = new LeaveBalance();
        $this->attAttGroupModel = new AttGroup();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){
        $attGroupsData = $this->getAttGroupsData($request);
        $date = $this->getFinancialYearDate($request);
        $datein  = $date['datein'];
        $dateout = $date['dateout'];
        return view('hrpayroll.setup.leave_balance',compact('attGroupsData','datein','dateout'));
    }

    public function getFinancialYearDate(Request $request){

        $date = array();
        $datein = $request->session()->get('financialyeardatefrom', 0);
        $datein   = strtotime($datein);
        $datein = date('Y-m-d',$datein);
        $dateout = $request->session()->get('financialyeardateto', 0);
        $dateout   = strtotime($dateout);
        $dateout = date('Y-m-d',$dateout);
        $date['datein'] =  $datein;
        $date['dateout'] = $dateout;
        return $date;
    }

    public function getData($request, $id=0){

        return $this->attLeaveBalanceModel->getLeaveBalances($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function fillGrid(Request $request){

        $modelData=$this->getData($request);
        foreach ($modelData as $key => $value) {
            $value->datein = strtotime($value->datein);
            $value->datein = date('d/m/Y',$value->datein); 
            $value->dateout = strtotime($value->dateout);
            $value->dateout = date('d/m/Y',$value->dateout); 
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

        $datefrom = date('Y', strtotime($request->datein));
        $dateto = date('Y', strtotime($request->dateout));
        $attgroup = $request->attgroupid; 
        
        $leaveBalance = LeaveBalance::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();
        
        if ($leaveBalance) {
        
            return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
        }

        $res = LeaveBalance::where('attgroupid', $attgroup)->where('datein','like',$datefrom . '%')->where('dateout','like',$dateto . '%')->where('id', '<>', $request->id)->first();
    
        if ($res) {
    
           return redirect()->back()->withInput($request->input())->with('error', 'Leave Balance already Added for this Attendance Group');
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

    public function getAttGroupsData(Request $request, $id=0){
        
        $attGroups = $this->attAttGroupModel->getAttGroups($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=',1)->where('isleave','=',1)->pluck('vname','id')->toArray();
        natcasesort($attGroups);
        return $attGroups;
    }

}
