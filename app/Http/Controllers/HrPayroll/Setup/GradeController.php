<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utils;
use App\User;
use DataTables,Auth;
use App\Models\HrPayroll\Setup\Grade;

class GradeController extends Controller
{

    public $utilsModel;
    public $attGradeModel;

    public function __construct() {

        $this->utilsModel = new Utils();
        $this->attGradeModel = new Grade();
    }

    public function index(){

        return view('hrpayroll.setup.grade');
    }

    public function getData($request, $id=0){         
        return $this->attGradeModel->getGrades($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        
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
                'data' => 'Grade doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){

        $vcode = Grade::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();

        if ($vcode) {

            return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
        }
        
        if($request->id){
            
            $id=$request->id;

            return $this->attGradeModel->updateGrade($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
            
        } else {

            return $this->attGradeModel->createGrade($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){

        return $this->attGradeModel->deleteGrade($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }
}
