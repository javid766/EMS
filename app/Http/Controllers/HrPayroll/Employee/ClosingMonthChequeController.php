<?php

namespace App\Http\Controllers\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Employee\ClosingMonthCheque;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\Setup\Bank;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class ClosingMonthChequeController extends Controller
{
    public $closingMonthChequeModel;
    public $employeeInfoModel;
    public $banksModel;
    public $utilsModel;

    public function __construct() {
        $this->closingMonthChequeModel = new ClosingMonthCheque();
        $this->employeeInfoModel = new EmployeeInfo();
        $this->banksModel = new Bank();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){

        $vtype = array('1'=>'TAX','2'=>'Emp Salary','3' => 'Cash','4' => 'Cover Letter');
        $empNames = $this->getEmployees($request);
        $banks    = $this->getBanks($request);
        return view('hrpayroll.employee.closing_month_cheque', compact('banks','vtype','empNames'));
    }

    public function getData($request, $id=0){

        return $this->closingMonthChequeModel->getClosingMonthCheques($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

    }

    public function fillGrid(Request $request){
        
        $datein = $request->vdate; 
        $first_date_find = strtotime(date("Y-m-d", strtotime($datein)) . ", first day of this month");
        $first_date = date("Y-m-d",$first_date_find);
        $request->request->add(['datein' => $first_date]);
        
        $last_date_find = strtotime(date("Y-m-d", strtotime($datein)) . ", last day of this month");
        $last_date = date("Y-m-d",$last_date_find);
        $request->request->add(['dateout' => $last_date]);
        $request->request->add(['employeeid'   => 0]);
        $modelData=$this->getData($request);

        foreach ($modelData as $key => $value) {
        
            $value->employeename = $value->empcode." - ". $value->employeename;
            $vdate = date("d/m/Y",strtotime($value->vdate)); 
            $value['vdate'] = $vdate;
            if ($value->vtype == 1) {
                $value->vtype = 'TAX';
            }
            if ($value->vtype == 2) {
                $value->vtype = 'Emp Salary';
            }
            if ($value->vtype == 3) {
                $value->vtype = 'Cash';
            }
            if ($value->vtype == 4) {
                $value->vtype = 'Cover Letter';
            }
            $chequedate = date("d/m/Y",strtotime($value->chequedate)); 
            $value['chequedate'] = $chequedate;
        }
        return Datatables::of($modelData)
        ->addColumn('action', function($data){
            return ('<div class="table-actions">
                <button id="editBtn" data="'.$data['id'].','.$data['vdate'].','.$data['employeeid'].'" class="btn btn-info btn-icon"><i class="ik ik-edit"></i></button>

                <button id="deleteBtn" class="btn btn-danger btn-icon"  data="'.$data['id'].'" data-toggle="modal" data-target="#deleteModal"><i class="ik ik-trash-2"></i></button> 
                </div>');
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function fillForm(Request $request, $id){
        
        $datein = $request->vdate; 
        $datein = str_replace('-', '/', $datein);
        $dateout = date('Y-m-d',strtotime($datein . "+1 days"));
        $request->request->add(['datein'   => $datein]);
        $request->request->add(['dateout'   => $dateout]);
        $request->request->add(['employeeid'   => 0]);
        $modelData=$this->getData($request, $id);
        if ($modelData) {
            $modelData = $modelData[0];
            $vdate = strtotime($modelData->vdate);
            $vdate = date('Y-m-d',$vdate); 
            $modelData['vdate']=$vdate;
            $chequedate = strtotime($modelData->chequedate);
            $chequedate = date('Y-m-d',$chequedate); 
            $modelData['chequedate']=$chequedate;
            return response($modelData);
        }
        else{
            return response(array(
                'error' => 1,
                'data' => 'Closing Month Cheque doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){

        if($request->id){

            $id=$request->id;

            return $this->closingMonthChequeModel->updateClosingMonthCheque($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
            
        } else {

            return $this->closingMonthChequeModel->createClosingMonthCheque($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){
        return $this->closingMonthChequeModel->deleteClosingMonthCheque($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function getBanks(Request $request, $id=0){
        $banks =  $this->banksModel->getBanks($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($banks);
        return $banks;
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
