<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\FinancialYear;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class FinancialYearController extends Controller
{
	public $accFinancialYearModel;
	public $utilsModel;

	public function __construct() {

		$this->accFinancialYearModel = new FinancialYear();
		$this->utilsModel = new Utils();
	}

	public function index(){
		return view('hrpayroll.setup.financial_year');
	}

	public function getData($request, $id=0){
		return $this->accFinancialYearModel->getAccFinancialYears($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
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

		$datefrom = strtotime($modelData->datefrom);
		$dateto = strtotime($modelData->dateto);
		$datefrom = date('Y-m-d',$datefrom); 
		$dateto = date('Y-m-d',$dateto);
		$modelData['datefrom']=$datefrom;
		$modelData['dateto']=$dateto;

		if($modelData){
			return response($modelData);
		}
		else{
			return response(array(
				'error' => 1,
				'data' => 'Financial Year doesn\'t exists!!',
			));
		}

	}

	public function save(Request $request){

		$accFinancialYear = FinancialYear::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();

		if ($accFinancialYear) {

			return redirect()->back()->withInput($request->input())->with('error','Code/Title already exists.');
		}
		
		if($request->id){

			$id=$request->id;

			return $this->accFinancialYearModel->updateAccFinancialYear($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

		} else {

			return $this->accFinancialYearModel->createAccFinancialYear($request, $this->utilsModel->CALL_TYPE_DEFAULT);
		}
	}

	public function delete($id){

		return $this->accFinancialYearModel->deleteAccFinancialYear($id, $this->utilsModel->CALL_TYPE_DEFAULT);
	}
}
