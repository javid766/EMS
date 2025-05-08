<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Ramazan;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class RamazanController extends Controller
{
	public $attRamazanModel;
	public $utilsModel;

	public function __construct() {

		$this->attRamazanModel = new Ramazan();
		$this->utilsModel = new Utils();

	}

	public function index(){
		return view('hrpayroll.setup.ramazan');
	}

	public function getData($request, $id=0){

		return $this->attRamazanModel->getRamazans($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
		
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
		$modelData['vname']=$modelData->vname;
		$modelData['vcode']=$modelData->vcode;

		$modelData['datefrom'] = strtotime($modelData->datefrom);
		$modelData['datefrom'] = date('Y-m-d',$modelData['datefrom']);
		$modelData['dateto'] = strtotime($modelData->dateto);
		$modelData['dateto'] = date('Y-m-d',$modelData['dateto']); 
		
		if($modelData){
			return response($modelData);
		}
		else{
			return response(array(
				'error' => 1,
				'data' => 'Ramazan doesn\'t exists!!',
			));
		}

	}

	public function save(Request $request){

		$ramazan = Ramazan::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();

		if ($ramazan) {

			return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
		}

		if($request->id){

			$id = $request->id;
			return $this->attRamazanModel->updateRamazan($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
			
		} else {

			return $this->attRamazanModel->createRamazan($request, $this->utilsModel->CALL_TYPE_DEFAULT);
		}
	}

	public function delete($id){

		return $this->attRamazanModel->deleteRamazan($id, $this->utilsModel->CALL_TYPE_DEFAULT);
	}
}
