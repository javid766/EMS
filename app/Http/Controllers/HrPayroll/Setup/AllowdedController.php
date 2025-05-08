<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Allowded;
use App\Models\HrPayroll\Setup\AllowdedGroup;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class AllowdedController extends Controller
{
	public $attAllowdedModel;
	public $utilsModel;
	public $attAllowedGroupModel;

	public function __construct() {

		$this->attAllowdedModel = new Allowded();
		$this->attAllowedGroupModel = new AllowdedGroup();
		$this->utilsModel = new Utils();

	}

	public function index(Request $request){
		$allowdedtype=array(
			'Allowance'=>'Allowance',
			'Deduction'=>'Deduction'
		);
		$amounttype=array(
			'Amount'=>'Amount',
		);
		$allowdedGrp = $this->getAllowdedGroups($request);
		
		return view('hrpayroll.setup.allowded',compact('allowdedGrp','allowdedtype','amounttype'));
	}

	public function getData($request, $id=0){

		return $this->attAllowdedModel->getAllowdeds($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

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
				'data' => 'ATT Allowded doesn\'t exists!!',
			));
		}

	}

	public function save(Request $request){

		$allowded = Allowded::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();
			
		if ($allowded) {

			return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
		}
		
		if($request->id){

			$id = $request->id;
			
			return $this->attAllowdedModel->updateAllowded($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

		} else {

			return $this->attAllowdedModel->createAllowded($request, $this->utilsModel->CALL_TYPE_DEFAULT);
		}
	}

	public function delete($id){

		return $this->attAllowdedModel->deleteAllowded($id, $this->utilsModel->CALL_TYPE_DEFAULT);
	}
	public function getAllowdedGroups($request){

		$allowdedGrps=array();
		$allowdedGrpsData = $this->attAllowedGroupModel->getAllowdedGroups($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT)->pluck('vtype', 'id');
		// foreach ($allowdedGrpsData as  $value) {
		// 	$allowdedGrps[$value['id']]= $value['vname'];
		// }
		return $allowdedGrpsData;
	}

	
}
