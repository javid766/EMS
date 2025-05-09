<?php

namespace App\Models\HrPayroll\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Utils;
use Auth;

class SalAdvance extends Model
{
	use HasFactory;
	protected $table = 'att_sal_advance';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = 'employee/advance/';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getSalAdvances($request, $id, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				'companyid' => 'required | integer',
				'locationid' => 'required | integer',
				'employeeid' => 'required' ,
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

		$salAdvance = SalAdvance::hydrate(
			DB::select('CALL sp_att_sal_advance_get('. $id .', '. $request->employeeid .', '. $userid .', '. $companyid .', '. $locationid .')')
		);

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'sal_advance' => $salAdvance,
				'status' => 'success'
			]);

		} else {
			foreach ($salAdvance as $key => $value) {
				$amount=$value->amount;
				$value['amount']=intval($amount);
			}
			return $salAdvance;
		}
	}

	public function createSalAdvance($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Salary Advance created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating advance against salary.', $type, $this->PAGE_LINK);
		}
	}

	public function updateSalAdvance($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Salary Advance updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteSalAdvance($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_att_sal_advance_insertupdate(
				?, 0, 0, 0, 0, NOW(),NOW(),0, 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			return $this->utilsModel->returnResponseStatusMessage('success', 'Salary Advance deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Salary Advance id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'employeeid' => 'required | integer',
			'amount' => 'required ',
			'bankid' => 'required | integer',
			'chequeno' => 'required ',
			'chequedate' => 'required ',
			'datein' => 'required ',
			'companyid'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedby' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip' => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
		]);
	}

	public function createUpdate($request, $id, $sp_type, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$insertedBy = $request->insertedby;
			$insertedIp = $request->insertedip;
			$updatedBy = $request->updatedby;
			$updatedIp = $request->updatedip;

			$companyid = $request->companyid;
			$locationid = $request->locationid;

		} else {

			$insertedBy = Auth::id();
			$insertedIp = $request->ip();
			$updatedBy  = Auth::id();
			$updatedIp  = $request->ip();

			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}

		return DB::select('CALL sp_att_sal_advance_insertupdate(
			?,
			'. $request->employeeid .',
			'. $request->amount .',
			'. $request->bankid .',
			"'. $request->chequeno .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->chequedate)) .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->datein)) .'",
			"'. $request->remarks .'",
			'.  $companyid .',
			'.  $locationid .',
			'.  $insertedBy  .',
			"'. $insertedIp .'",
			'.  $updatedBy  .',
			"'. $updatedIp .'",
			"'. $sp_type .'")',
			[
				$id
			]
		)[0]->id;
	}
}
