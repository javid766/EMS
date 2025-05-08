<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Company;
use App\Models\HrPayroll\Setup\Country;
use App\Models\User\UserCompany;
use App\Models\HrPayroll\Setup\Currency;

use App\Models\Utils;
use App\User;
use DataTables,Auth;

class CompanyController extends Controller
{
	public $utilsModel;
	public $companyModel;
	public $userCompanyModel;

	public function __construct() {

		$this->utilsModel 		= new Utils();
		$this->companyModel 	= new Company();
		$this->countryModel 	= new Country();
		$this->accCurrencyModel = new Currency();
		$this->userCompanyModel = new UserCompany();

	}

	public function index(Request $request){

		$compdateformat=array(
			'mm/dd/yyyy'=>'mm-dd-yyyy',
			'yyyy/mm/dd'=>'yyyy-mm-dd',
			'dd/mm/yyyy' =>'dd-mm-yyy'
		);
		$currencies = $this->getCurrencies($request);
		$countries= $this->getCountries($request);

		return view('hrpayroll.setup.company', compact('currencies','countries','compdateformat'));
		
	}
	
	public function getData($request, $id=0){

		if ($id == 0) {

			if (Auth::user()->isSuperAdmin()) {

	            $companies = $this->companyModel->getCompanies($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT);

	        } else {
	        	
	        	$companies = $this->companyModel->getCompanies($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT)->where('tid', Auth::user()->tid);
	        }

		} else {

			$companies = $this->companyModel->getCompanies($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
		}

		return $companies;
	}

	public function fillGrid(Request $request){
		
		$modelData = $this->getData($request);

		return Datatables::of($modelData)
		->addColumn('action', function($data){
			return ('<div >
				<a  id ="editBtn" href="#" data="'.$data['id'].'">Select</a>
				</div>');
		})
		->rawColumns(['action'])
		->make(true);
	}

	public function fillForm(Request $request, $id){

		$modelData = $this->getData($request, $id)[0];

		if($modelData['logo']){

			$logo=$modelData['logo'];
			$imgsrc = '../'.$logo;  
			$modelData['logoImgsrc'] = $imgsrc;
		}

		if($modelData['signature_image']){

			$logo=$modelData['signature_image'];
			$imgsrc = '../'.$logo;  
			$modelData['signImgsrc'] = $imgsrc;

		}

		if($modelData){

			return response($modelData);

		} else {

			return response(array(
				'error' => 1,
				'data' => 'Company doesn\'t exists!!',
			));
		}
	}

	public function save(Request $request){

		if($request->logo) {

			$file = $request->logo;
			$file_name = $file->getClientOriginalName();
			$destinationPath = public_path('images/company/'.str_replace(' ', '-', strtolower($request->vcode)).'/logo/');
			$fileSavePath = "images/company/".str_replace(' ', '-', strtolower($request->vcode)).'/logo';
			$file->move($destinationPath, $file_name);
			$request->logo = $fileSavePath."/".$file_name;
		
		} else {
		
			$request->request->add(['logo' => '']);
		}

		if($request->signature_image) {

			$file = $request->signature_image;
			$file_name = $file->getClientOriginalName();
			$destinationPath = public_path('images/company/'.str_replace(' ', '-', strtolower($request->vcode)).'/signature_image/');
			$fileSavePath = "images/company/".str_replace(' ', '-', strtolower($request->vcode)).'/signature_image';
			$file->move($destinationPath, $file_name);
			$request->signature_image = $fileSavePath."/".$file_name;
		
		} else {

			$request->request->add(['signature_image' => '']);
		}

		$company = Company::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();

		if ($company) {

			return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
		}

		if($request->id){
			
			$id = $request->id;

			return $this->companyModel->updateCompany($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

		} else {

			return $this->companyModel->createCompany($request, $this->utilsModel->CALL_TYPE_DEFAULT);
		}
	}

	public function delete($id){

		return $this->companyModel->deleteCompany($id, $this->utilsModel->CALL_TYPE_DEFAULT);
	}

	public function search(){

		return view('hrpayroll.setup.company_search');
	}

	public function getCurrencies($request){

		$currenciesData=$this->accCurrencyModel->getCurrencies($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
		natcasesort($currenciesData);
		return $currenciesData;
	}

	public function getCountries($request){

		$countriesData=$this->countryModel->getCountries($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
		natcasesort($countriesData);
		return $countriesData;
	}
}
