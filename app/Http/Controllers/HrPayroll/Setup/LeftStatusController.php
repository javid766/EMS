<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\LeftStatus;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class LeftStatusController extends Controller
{
    public $attLeftStatusModel;
    public $utilsModel;

    public function __construct() {

        $this->attLeftStatusModel = new LeftStatus();
        $this->utilsModel = new Utils();
    }


    public function index(){

        return view('hrpayroll.setup.left_status');
    }

    public function getData($request, $id=0){
           
        return $this->attLeftStatusModel->getLeftStatuses($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
         
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
                'data' => 'Left Status doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){

        $leftStatus = LeftStatus::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();

        if ($leftStatus) {

            return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
        }
        
        if($request->id){
            
            $id=$request->id;

            return $this->attLeftStatusModel->updateLeftStatus($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        
        } else {

            return $this->attLeftStatusModel->createLeftStatus($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){

        return $this->attLeftStatusModel->deleteLeftStatus($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }
}
