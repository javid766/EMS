<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Tenant;
use App\Models\Utils;
use DataTables,Auth;

class TenantController extends Controller
{
    public $setupTenantModel;
    public $utilsModel;

    public function __construct() {

        $this->setupTenantModel = new Tenant();
        $this->utilsModel = new Utils();
    }

    public function index(){


        return view('hrpayroll.setup.tenant');
    }

    public function getParams(){

        $userId=Auth::id();
        $companyId=Auth::user()->default_companyid;
        $locationid = 0;
        $params=array(
            'userid' => $userId,
            'companyid' => $companyId,
            'locationid' => $locationid
        );
        $request = (object)$params;
        return $request;
    }

    public function getData(Request $request , $id=0){
           
        return $this->setupTenantModel->getTenants($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
         
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
        if($modelData){
            return response($modelData);
        }
        else{
            return response(array(
                'error' => 1,
                'data' => 'Location doesn\'t exists!!',
            ));
        }
    }


    public function save(Request $request){
        
        if($request->id){
            
            $id=$request->id;

            return $this->setupTenantModel->updateTenant($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        
        } else {

            return $this->setupTenantModel->createTenant($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){

        return $this->setupTenantModel->deleteTenant($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }
}
