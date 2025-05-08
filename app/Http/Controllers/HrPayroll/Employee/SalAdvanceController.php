<?php

namespace App\Http\Controllers\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Bank;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\Employee\SalAdvance;
// use App\Models\HrPayroll\Setup\FinancialYear;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class SalAdvanceController extends Controller
{
    public $employeeInfoModel;
    public $banksModel;
    public $salAdvanceModel;
    public $accFinancialYearModel;
    public $utilsModel;

    public function __construct() {
        $this->employeeInfoModel = new EmployeeInfo();
        $this->salAdvanceModel = new SalAdvance();
        // $this->accFinancialYearModel = new FinancialYear();
        $this->banksModel = new Bank();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){
        $employees = $this->getEmployees($request);
        $banks    = $this->getBanks($request);
        return view('hrpayroll.employee.advance_entry',compact('banks','employees'));
    }

    public function getData($request, $id=0){

        $request->request->add(['employeeid' => '0']); //add request    
        return $this->salAdvanceModel->getSalAdvances($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function fillGrid(Request $request){
        
        $modelData=$this->getData($request);
        foreach ($modelData as $value) {
            $posteddate = date("d/m/Y",strtotime($value->posteddate)); 
            $value['posteddate'] = $posteddate;
            $chequedate = date("d/m/Y",strtotime($value->chequedate)); 
            $value['chequedate'] = $chequedate;
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
        $posteddate = date("Y-m-d",strtotime($modelData->posteddate)); 
        $modelData['posteddate'] = $posteddate;
        $chequedate = date("Y-m-d",strtotime($modelData->chequedate)); 
        $modelData['chequedate'] = $chequedate;

        if($modelData){
            return response($modelData);
        }
        else{
            return response(array(
                'error' => 1,
                'data' => 'Advance Entry doesn\'t exists!!',
            ));
        }

    } 

    public function save(Request $request){

        // $flag = $this->validateFinancialYear($request);
        // if (empty($flag)) {
        //      return redirect()->back()->withInput($request->input())->with('error' , "Please select Date in between Financial Year");
        // }

        if($request->id){
            $id=$request->id;
            return $this->salAdvanceModel->updateSalAdvance($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);          
        } else {
            
            return $this->salAdvanceModel->createSalAdvance($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){
        return $this->salAdvanceModel->deleteSalAdvance($id, $this->utilsModel->CALL_TYPE_DEFAULT);
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
    
    // public function validateFinancialYear(Request $request){
    //     return $this->accFinancialYearModel->validateFinancialYear($request,0, $this->utilsModel->CALL_TYPE_DEFAULT)->toArray();
    // }

}
