<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Country;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class CountryController extends Controller
{
	public $countryModel;
	public $utilsModel;

	public function __construct() {

		$this->countryModel = new Country();
		$this->utilsModel = new Utils();

	}

	public function index(Request $request){

		return view('hrpayroll.setup.country');
	}

	public function getData($request, $id=0){
			
		return $this->countryModel->getCountries($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
		 
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
				'data' => 'Country doesn\'t exists!!',
			));
		}

	}

	public function save(Request $request){
		
		$code = Country::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();

		if ($code) {
			
			return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
		}

		if($request->id){
			
			$id=$request->id;

			return $this->countryModel->updateCountry($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
		
		} else {

			return $this->countryModel->createCountry($request, $this->utilsModel->CALL_TYPE_DEFAULT);
		}
	}

	public function delete($id){

		return $this->countryModel->deleteCountry($id, $this->utilsModel->CALL_TYPE_DEFAULT);
	}	
}
