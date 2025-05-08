<?php

namespace App\Http\Controllers\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\Employee\EmployeeFixTax;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class FixTaxController extends Controller
{

    public $employeeInfoModel;
    public $empFixTaxModel;
    public $utilsModel;

    public function __construct() {
        $this->employeeInfoModel = new EmployeeInfo();
        $this->empFixTaxModel = new EmployeeFixTax();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){
        $empNames = $this->getEmployees($request);
        return view('hrpayroll.employee.fix_tax',compact('empNames'));
    }

    public function getData($request, $id=0){
        return $this->empFixTaxModel->getEmpFixTax($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

    }

    public function fillGrid(Request $request){
        $modelData=$this->getData($request);
        foreach ($modelData as $value) {
            $vdate = date("d/m/Y",strtotime($value->vdate));
            $vdatefrom = date("d/m/Y",strtotime($value->vdatefrom)); 
            $value['vdate'] = $vdate;
            $value['vdatefrom'] = $vdatefrom;
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
         $vdate = date("Y-m-d",strtotime($modelData->vdate));
         $vdatefrom = date("Y-m-d",strtotime($modelData->vdatefrom)); 
         $modelData['vdate'] = $vdate;
         $modelData['vdatefrom'] = $vdatefrom;
         if($modelData){
            return response($modelData);
        }
        else{
            return response(array(
                'error' => 1,
                'data' => 'Employee Fix Tax doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){

        if($request->id){

            $id=$request->id;

            return $this->empFixTaxModel->updateEmpFixTax($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
            
        } else {

            return $this->empFixTaxModel->createEmpFixTax($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){

        return $this->empFixTaxModel->deleteEmpFixTax($id, $this->utilsModel->CALL_TYPE_DEFAULT);
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
