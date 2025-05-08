<?php

namespace App\Http\Controllers\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\Employee\LocalSale;
use App\Models\HrPayroll\Setup\SaleTypes;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class LocalSaleController extends Controller
{

    public $employeeInfoModel;
    public $localSaleModel;
    public $saleTypesModel;
    public $utilsModel;

    public function __construct() {
        $this->employeeInfoModel = new EmployeeInfo();
        $this->localSaleModel    = new LocalSale();
        $this->saleTypesModel    = new SaleTypes();
        $this->utilsModel        = new Utils();
    }

    public function index(Request $request){
        $employees = $this->getEmployees($request);
        $saleTypes = $this->getSaleTypes($request);  
        return view('hrpayroll.employee.local_sale',compact('employees', 'saleTypes'));
    }

    public function getData($request, $id=0){
        return $this->localSaleModel->getLocalSale($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

    }

    public function fillGrid(Request $request){
        $modelData=$this->getData($request);
        foreach ($modelData as $value) {
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
         $vdate = date("Y-m-d",strtotime($modelData->vdate)); 
         $modelData['vdate'] = $vdate;
         if($modelData){
            return response($modelData);
        }
        else{
            return response(array(
                'error' => 1,
                'data' => 'Employee Local Sale doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){
        if($request->id){
            $id=$request->id;
            return $this->localSaleModel->updatelocalSale($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);         
        } else {
            return $this->localSaleModel->createlocalSale($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){
        return $this->localSaleModel->deletelocalSale($id, $this->utilsModel->CALL_TYPE_DEFAULT);
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

    public function getSaleTypes(Request $request, $id=0){
        $saleData = $this->saleTypesModel->getSaleTypes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->pluck('vname','id')->toArray();
        natcasesort($saleData);
        return $saleData;
    }


}
