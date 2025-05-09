<?php

namespace App\Models\HrPayroll\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Utils;

class Company extends Model
{
    use HasFactory;

    protected $table = 'setup_company';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/setup/company';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getCompanies($request, $id, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				'companyid' => 'required | integer',
				'locationid' => 'required | integer',
			]);

			if($validator->fails()) {

				return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
			}

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}

		// if ($companyid == 0) {

		// 	return $this->utilsModel->returnResponseStatusMessage('error', "Session expired please login again", $type, '/login');
		// }

		$companies = Company::hydrate(
			DB::select('CALL sp_setup_company_get('. $id .', '. $userid .', '. $companyid .', '. $locationid .')')
		);
		
		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'companies' => $companies,
				'status' => 'success'
			]);

		} else {

			return $companies;
		}
		

	}

	public function createCompany($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Company created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating company.', $type, $this->PAGE_LINK);
		}
	}

	public function updateCompany($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Company updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteCompany($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_setup_company_insertupdate(
				?, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			return $this->utilsModel->returnResponseStatusMessage('success', 'Company deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Company id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'vcode'				=> 'required',
			'vname'				=> 'required',
			'tid' 				=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'company_nature' 	=> 'required',
			//'url'				=> 'required',
			//'display_url'		=> 'required',
			//'logo'			=> 'required',
			//'signature_image'	=> 'required',
			//'timezone'		=> 'required',
			//'phone'			=> 'required',
			//'uan'				=> 'required',
			//'fax'				=> 'required',
			//'address'			=> 'required',
			//'city'				=> 'required',
			'countryid'			=> 'required | integer',
			'currencyid'		=> 'required | integer',
			'shipping_methodid' => 'required | integer',
			'havesaletax' 		=> 'integer',
			'saletaxper' 		=> 'required',
			'havevat' 			=> 'integer',
			'vatper'			=> 'required',
			//'ntn_heading' 	=> 'required',
			//'cnic_heading' 	=> 'required',
			//'registrationno' 	=> 'required',
			//'salestaxno' 		=> 'required',
			//'sqldateformat' 	=> 'yyyy-mm-dd',
			//'compdateformat' 	=> 'required',
			//'isactive'		  	=> 'integer',
			'insertedby'	  	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip'	  	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'		  	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'		  	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
		]);
	}

	public function createUpdate($request, $id, $sp_type, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$tid = $request->tid;
			$insertedBy = $request->insertedby;
            $insertedIp = $request->insertedip;
            $updatedBy = $request->updatedby;
            $updatedIp = $request->updatedip;

		} else {

			$tid = Auth::user()->tid;
			$insertedBy = Auth::id();
			$insertedIp = $request->ip();
			$updatedBy = Auth::id();
			$updatedIp = $request->ip();
			$sqldateformat="yyyy-mm-dd";

		}

		return DB::select('CALL sp_setup_company_insertupdate(
			?,
			"'. $request->vcode .'",
			"'. trim($request->vname) .'",
			'.  $tid .',
			"'. $request->company_nature .'",
			"'. (isset($request->url) ? $request->url : '-') .'",
			"'. (isset($request->display_url) ? $request->display_url : '-') .'",
			"'. $request->logo .'",
			"'. $request->signature_image .'",
			"'. (isset($request->timezone) ? $request->timezone : '-') .'",
			"'. (isset($request->phone) ? $request->phone : '-') .'",
			"'. (isset($request->uan) ? $request->uan : '-') .'",
			"'. (isset($request->fax) ? $request->fax : '-') .'",
			"'. (isset($request->address) ? $request->address : '-') .'",
			"'. (isset($request->city) ? $request->city : '-') .'",
			'. $request->countryid .',
			'. $request->currencyid .',
			'. $request->shipping_methodid .',
			'. (isset($request->havesaletax) ? $request->havesaletax : 0) .',
			"'. $request->saletaxper .'",
			'. (isset($request->havevat) ? $request->havevat : 0) .',
			"'. $request->vatper .'",
			"'. (isset($request->ntn_heading) ? $request->ntn_heading : '-') .'",
			"'. (isset($request->cnic_heading) ? $request->cnic_heading : '-') .'",
			"'. (isset($request->registrationno) ? $request->registrationno : '-') .'",
			"'. (isset($request->salestaxno) ? $request->salestaxno : '-') .'",
			"'. $sqldateformat .'",
			"'. (isset($request->compdateformat) ? $request->compdateformat : '-') .'",
			'. (isset($request->isactive) ? $request->isactive : 0) .',
			'.  $insertedBy  .',
			"'. $insertedIp .'",
			'.  $updatedBy .',
			"'. $updatedIp .'",
			"'. $sp_type .'")',
			[
				$id
			]
		)[0]->id;
	}
}
