<?php

namespace App\Models\HrPayroll\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Utils;
use Auth;

class SalLoan extends Model
{
	use HasFactory;
	protected $table = 'att_sal_loan';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = 'employee/loan/';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getSalLoans($request, $id, $type) {

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

			$validator = Validator::make($request->all() ,[
				'employeeid' => 'required' ,
			]);

			if($validator->fails()) {

				return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
			}

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}

		$employeeid = $request->employeeid;
		$salLoan = SalLoan::hydrate(
			DB::select('CALL sp_att_sal_loan_get('. $id .', '. $employeeid .', '. $userid .', '. $companyid .', '. $locationid .')')
		);

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Loan", "ErrorMsg"=>"CALL sp_att_sal_loan_get($id, $employeeid, $userid,$companyid, $locationid)");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'sal_loan' => $salLoan,
				'status' => 'success'
			]);

		} else {
			foreach ($salLoan as $key => $value) {
				$amount=$value->amount;
				$installment=$value->installment;
				$value['amount']=intval($amount);
				$value['installment']=intval($installment);
			}
			return $salLoan;
		}
	}

	public function createSalLoan($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Loan Entry created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating loan entry.', $type, $this->PAGE_LINK);
		}
	}

	public function updateSalLoan($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Loan Entry updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteSalLoan($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_att_sal_loan_insertupdate(
				?, 0, 0, 0, 0, 0, 0, NOW(), 0, NOW(), 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Loan", "ErrorMsg"=>"SET @id = $id; CALL sp_att_sal_loan_insertupdate(@id, 0, 0, 0, 0, 0, 0, NOW(), 0, NOW(), 0, 0, 0, 0, 0, 0, '".$this->utilsModel->SP_ACTION_DELETE."')");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */

			return $this->utilsModel->returnResponseStatusMessage('success', 'Loan Entry deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Loan id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'employeeid' => 'required | integer',
			'amount' => 'required ',
			'installment' => 'required ',
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

		if($sp_type == 'u'){
			$set_id = "SET @id = $id;";
		}else{
			$set_id = "";
		}


		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Loan", "ErrorMsg"=>"$set_id CALL sp_att_sal_loan_insertupdate(@id, $request->employeeid, $request->amount, $request->installment, $request->bankid, $request->allowdedid, '".$request->chequeno."',    '".date('Y-m-d 00:00:00', strtotime($request->chequedate))."', '$request->remarks', '".date('Y-m-d 00:00:00', strtotime($request->datein))."', $companyid, 1, $insertedBy, '$insertedIp', $updatedBy, '$updatedIp', '$sp_type')");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */

		return DB::select('CALL sp_att_sal_loan_insertupdate(
			?,
			'. $request->employeeid .',
			'. $request->amount .',
			'. $request->installment .',
			'. $request->bankid .',
			'. $request->allowdedid .',
			"'. $request->chequeno .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->chequedate)) .'",
			"'. $request->remarks .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->datein)) .'",
			'.  $companyid .',
			1,
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
