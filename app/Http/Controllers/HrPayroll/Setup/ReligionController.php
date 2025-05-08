<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Religion;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class ReligionController extends Controller
{
	public $attReligionModel;
	public $utilsModel;

	public function __construct() {

		$this->attReligionModel = new Religion();
		$this->utilsModel = new Utils();

	}

	public function index(){

		return view('hrpayroll.setup.religion');
	}

	public function getData($request, $id=0){

		return $this->attReligionModel->getReligions($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

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
				'data' => 'Religions doesn\'t exists!!',
			));
		}

	}

	public function save(Request $request){
	
		$vcode = Religion::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();

		if ($vcode) {

			return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
		}
		
		if($request->id){

			$id = $request->id;
			return $this->attReligionModel->updateReligion($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
			
		} else {

			return $this->attReligionModel->createReligion($request, $this->utilsModel->CALL_TYPE_DEFAULT);
		}
	}

	public function delete($id){

		return $this->attReligionModel->deleteReligion($id, $this->utilsModel->CALL_TYPE_DEFAULT);
	}
}
