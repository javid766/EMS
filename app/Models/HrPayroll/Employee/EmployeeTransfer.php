<?php

namespace App\Models\HrPayroll\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class EmployeeTransfer extends Model
{
    use HasFactory;
    protected $table = 'att_employee_location_transfer';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/employee/employeetransfer';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getEmpTransferS($request, $id, $type) {

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

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
		}

		$empFixTax = EmployeeTransfer::hydrate(
			DB::select('CALL sp_att_employee_transfer_get('. $id .', '. $userid .', '. $companyid .')')
		);

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'EmployeeTransfer' => $empFixTax,
				'status' => 'success'
			]);

		} else {

			return $empFixTax;
		}

	}
	public function getEmpLocationId($request, $id, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				'employeeid' => 'required | integer',
				'companyid' => 'required | integer',

			]);

			if($validator->fails()) {

				return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
			}

			$userid = $request->userid;
			$companyid = $request->companyid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
		}

		$empLocationid = EmployeeTransfer::hydrate(
			DB::select('CALL sp_att_employee_location_get('. $id .', '. $request->employeeid .', '. $userid .', '. $companyid .')'));

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'EmployeeTransfer' => $empLocationid,
				'status' => 'success'
			]);

		} else {

			return $empLocationid;
		}

	}
	public function createEmpTransfer($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Employee Transfer  created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating Employee Transfer.', $type, $this->PAGE_LINK);
		}
	}

	public function updateEmpTransfer($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Employee Transfer updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteEmpTransfer($id, $type) {
		
		if ($id > 0) {
			
			$result = DB::select('CALL sp_att_employee_transfer_insertupdate(
				?, 0, 0, 0,0, NOW(), 0, 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			return $this->utilsModel->returnResponseStatusMessage('success', 'Employee Transfer  deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Employee Transfer  id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			
			'employeeid'	  => 'required ',
			'etypeid' => 'required ',
			'locationid' => 'required ',
			'vdate'	  => 'required ',
			'companyid'   => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			// 'isactive'		  => 'required | integer',
			'insertedby'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'	  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'	  => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
		]);
	}

	public function createUpdate($request, $id, $sp_type, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$insertedBy = $request->insertedby;
            $insertedIp = $request->insertedip;
            $updatedBy = $request->updatedby;
            $updatedIp = $request->updatedip;
            $companyid = $request->companyid;
            $userid    = $request->userid;

		} else {

			$insertedBy = Auth::id();
			$insertedIp = $request->ip();
			$updatedBy = Auth::id();
			$updatedIp = $request->ip();
			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
		}
		return DB::select('CALL sp_att_employee_transfer_insertupdate(
			?,
			'. $request->employeeid .',
			'. $request->etypeid .',
			'. $request->locationid .',
			'. $request->oldlocationid .',
			"'. date('Y-m-d 00:00:00', strtotime($request->vdate)) .'",
			"'. $request->remarks .'",
			'. $userid .',
			'. $companyid .',
			'.  $insertedBy  .',
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
