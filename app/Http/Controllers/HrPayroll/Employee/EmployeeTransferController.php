<?php

namespace App\Http\Controllers\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\EType;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\Employee\EmployeeTransfer;
use App\Models\HrPayroll\Setup\Location;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class EmployeeTransferController extends Controller
{
    public $ETypeModel;
    public $locationtModel;
    public $employeeInfoModel;
    public $empTransferModel;
    public $utilsModel;

    public function __construct() {
        $this->ETypeModel = new EType();
        $this->locationtModel = new Location();
        $this->employeeInfoModel = new EmployeeInfo();
        $this->empTransferModel = new EmployeeTransfer();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){
        $allETypes = $this->getETypes($request);
        $locations  = $this->getLocations($request); 
        $empNames = $this->getEmployees($request);
        return view('hrpayroll.employee.employee_transfer',compact('allETypes','locations','empNames'));
    }

    public function getData($request, $id=0){

       return $this->empTransferModel->getEmpTransferS($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

    }

    public function fillGrid(Request $request){

        $modelData=$this->getData($request);
        foreach ($modelData as $key => $value) {
            $vdate = date("d/m/Y",strtotime($value->vdate)); 
            $value['vdate'] = $vdate;
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
     $vdate = strtotime($modelData->vdate);
     $vdate = date('Y-m-d',$vdate); 
     $modelData['vdate']=$vdate;
     if($modelData){
        return response($modelData);
    }
    else{
        return response(array(
            'error' => 1,
            'data' => 'Employee Transfer doesn\'t exists!!',
        ));
    }

    }

    public function save(Request $request){

        if($request->oldlocationid == $request->locationid){
            return redirect()->back()->withInput($request->input())->with('error' , "New and Old Location can't be same!!");
        }
        else{
            if($request->id){
                $id=$request->id;
                return $this->empTransferModel->updateEmpTransfer($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
            } else {

                return $this->empTransferModel->createEmpTransfer($request, $this->utilsModel->CALL_TYPE_DEFAULT);
            }
        }
        
    }

    public function delete($id){
        return $this->empTransferModel->deleteEmpTransfer($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function getETypes(Request $request, $id=0){

        $ETypeData = $this->ETypeModel->getETypes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($ETypeData);
        return $ETypeData;
    }

    public function getLocations(Request $request, $id=0){

        $locationData = $this->locationtModel->getLocations($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($locationData);
        return $locationData;
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
    public function getEmpLocationId(Request $request)
    {
        $request->request->add(['employeeid' => $request->employeeid]); 
        $location = $this->empTransferModel->getEmpLocationId($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT);
        return $location;
    }

}
