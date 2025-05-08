<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utils;
use App\Models\HrPayroll\Setup\DeptGroup;
use App\Models\HrPayroll\Setup\Dept;
use App\User;
use DataTables,Auth;

class DeptsController extends Controller
{

    public $utilsModel;
    public $deptGroupModel;
    public $deptModel;

    public function __construct() {
        $this->deptGroupModel = new DeptGroup();
        $this->deptModel      = new Dept();
        $this->utilsModel     = new Utils();

    }

    public function index(Request $request){
    	$deptgroups = $this->getDeptGroups($request);
        return view('hrpayroll.setup.dept', compact('deptgroups'));
    }

    public function getData($request, $id=0){
       
        return $this->deptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        
    }

    public function fillGrid(Request $request){
        
        $modelData=$this->getData($request)->where('id', '>', 0);
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
                'data' => 'Department doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){

        $vcode = Dept::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();

        if ($vcode) {

            return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
        }
        
        if($request->id){
            
            $id=$request->id;

            return $this->deptModel->updateDept($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
            
        } else {

            return $this->deptModel->createDept($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){

        return $this->deptModel->deleteDept($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function getDeptGroups($request){
      $id=0;
      $deptGroupData =  $this->deptGroupModel->getDeptGroups($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=',1)->pluck('VName','id')->toArray();
    natcasesort($deptGroupData);
     return $deptGroupData;
   
}



}
