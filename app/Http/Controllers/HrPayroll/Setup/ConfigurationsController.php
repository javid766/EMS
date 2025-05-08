<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utils;
use App\User;
use DataTables,Auth;
use App\Models\HrPayroll\Setup\AttGlobal;

class ConfigurationsController extends Controller
{

    public $utilsModel;
    public $attGlobalModel;

    public function __construct() {

        $this->utilsModel = new Utils();
       $this->attGlobalModel = new AttGlobal();
    }

    public function index(){

        return view('hrpayroll.setup.globals');
    }


    public function getData($request, $id=0){         
        return $this->attGlobalModel->getAttGlobals($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        
    }

    public function fillGrid(Request $request){    
        $modelData=$this->getData($request);
        foreach($modelData as $value){
            $value['vvalue'] = str_replace("/",'\\',$value->vvalue);
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
        $modelData['vvalue'] = str_replace("/",'\\',$modelData->vvalue);
        if($modelData){
            return response($modelData);
        }
        else{
            return response(array(
                'error' => 1,
                'data' => 'Globals doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){

        $request['vvalue'] = str_replace("\\",'/',$request->vvalue);

        $globals = AttGlobal::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();

        if ($globals) {

            return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
        }
        
        if($request->id){
            
            $id=$request->id;

            return $this->attGlobalModel->updateAttGlobal($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
            
        } else {

            return $this->attGlobalModel->createAttGlobal($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){

        return $this->attGlobalModel->deleteAttGlobal($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }
}
