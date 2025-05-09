<?php

namespace App\Models\HrPayroll\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Utils;
use Auth;

class FinancialYear extends Model
{
    use HasFactory;

    protected $table = 'acc_financial_year';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/setup/financial-year';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getAccFinancialYears($request, $id, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				'companyid' => 'required | integer',
			]);

			if($validator->fails()) {

				return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
			}

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationId = 0;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationId = $request->session()->get('locationid', 0);
		}

		if ($companyid == 0) {

			return $this->utilsModel->returnResponseStatusMessage('error', "Session expired please login again", $type, '/login');
		}

		$accFinancialYears = FinancialYear::hydrate(
			DB::select('CALL sp_acc_financial_year_get('. $id .', '. $userid .', '. $companyid .', '. $locationId .')')
		);

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'financial_years' => $accFinancialYears,
				'status' => 'success'
			]);

		} else {

			return $accFinancialYears;
		}

	}

	public function validateFinancialYear($request, $id, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				'companyid' => 'required | integer',
			]);

			if($validator->fails()) {

				return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
			}

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationId = 0;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationId = $request->session()->get('locationid', 0);
		}

		if ($companyid == 0) {

			return $this->utilsModel->returnResponseStatusMessage('error', "Session expired please login again", $type, '/login');
		}

		$accFinancialYears = FinancialYear::hydrate(
			DB::select('CALL sp_att_validate_financial_year('. $id .',  "'. $request->datein .'", '. $userid .', '. $companyid .', '. $locationId .')')
		);

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'financial_years' => $accFinancialYears,
				'status' => 'success'
			]);

		} else {

			return $accFinancialYears;
		}

	}

	public function createAccFinancialYear($request, $type) {


		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Financial Year created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating financial year.', $type, $this->PAGE_LINK);
		}
	}

	public function updateAccFinancialYear($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Financial Year updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteAccFinancialYear($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_acc_financial_year_insertupdate(
				?, 0, 0, NOW(), NOW(), 0, 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			)[0];

			if ($result->status == $this->utilsModel->API_VALIDATION_ERROR) {
				return $this->utilsModel->returnResponseStatusMessage('error', $result->msg, $type, $this->PAGE_LINK);
			}

			return $this->utilsModel->returnResponseStatusMessage('success', 'Financial Year deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Financial year id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'vcode'			  => 'required',
			'vname'			  => 'required',
			'datefrom'		  => 'required',
			'dateto'		  => 'required',
			'companyid' 		=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			// 'isactive'		  => 'required | integer',
			'insertedby'	  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip'	  => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'		  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'		  => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
		]);
	}

	public function createUpdate($request, $id, $sp_type, $type) {


		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$companyId = $request->companyid;
			$insertedBy = $request->insertedby;
            $insertedIp = $request->insertedip;
            $updatedBy = $request->updatedby;
            $updatedIp = $request->updatedip;

		} else {

			$companyId = $request->session()->get('companyid', 0);
			$insertedBy = Auth::id();
			$insertedIp = $request->ip();
			$updatedBy = Auth::id();
			$updatedIp = $request->ip();
		}

		return DB::select('CALL sp_acc_financial_year_insertupdate(
			?,
			"'. $request->vcode .'",
			"'. $request->vname .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->datefrom)) .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->dateto)) .'",
			'. (isset($request->istransactional) ? $request->istransactional : 0) .',
			'. $companyId .',
			'. (isset($request->isactive) ? $request->isactive : 0) .',
			'. $insertedBy .',
			"'. $insertedIp .'",
			'. $updatedBy .',
			"'. $updatedIp .'", 
			"'. $sp_type .'")',
			[
				$id
			]
		)[0]->id;
	}
}
