<?php

namespace App\Models\HrPayroll\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class LoanEntry extends Model
{
    use HasFactory;
    protected $table = 'emp_setup_loan';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/employee/loan';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getLoanEntries($request, $id, $type) {

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

		$loanEntry = LoanEntry::hydrate(
			DB::select('CALL sp_emp_setup_loan_get('. $id .', '. $userid .', '. $companyid .')')
		);


		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Loan", "ErrorMsg"=>"CALL sp_emp_setup_loan_get($id, $userid,$companyid)");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'loanEntry' => $loanEntry,
				'status' => 'success'
			]);

		} else {

			return $loanEntry;
		}

	}

	public function createLoanEntry($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Employee Loan Entry created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating Employee Loan Entry.', $type, $this->PAGE_LINK);
		}
	}

	public function updateLoanEntry($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Employee Loan Entry updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteLoanEntry($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_emp_setup_loan_insertupdate(
				?, 0, 0, NOW(), 0, 0,  NOW() , 0, 0, 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Loan", "ErrorMsg"=>"SET @id = $id; CALL sp_emp_setup_loan_insertupdate(@id, 0, 0, NOW(), 0, 0,  NOW() , 0, 0, 0, 0, 0, 0, 0, 0, '".$this->utilsModel->SP_ACTION_DELETE."')");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */

			return $this->utilsModel->returnResponseStatusMessage('success', 'Employee Loan Entry deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Employee Loan Entry id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'vtypeid'	  => 'required ',
			'empid'	  => 'required ',
			'vdate'	  => 'required ',
			'bankid'	  => 'required ',
			'chequeno'	  => 'required ',
			'chequedate'	  => 'required ',
			'amount'	  => 'required ',
			'installment'	  => 'required ',
			'remarks'	  => 'required ',
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

		} else {

			$insertedBy = Auth::id();
			$insertedIp = $request->ip();
			$updatedBy = Auth::id();
			$updatedIp = $request->ip();
			$companyid = $request->session()->get('companyid', 0);
		}

		if($sp_type == 'u'){
			$set_id = "SET @id = $id;";
		}else{
			$set_id = "";
		}


		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Loan", "ErrorMsg"=>"$set_id CALL sp_emp_setup_loan_insertupdate(@id, $request->vtypeid, $request->empid, '".date('Y-m-d 00:00:00', strtotime($request->vdate))."' , $request->bankid, '".$request->chequeno."', '".date('Y-m-d 00:00:00', strtotime($request->chequedate))."', $request->amount, $request->installment,  '".$request->remarks."', $companyid,  $insertedBy, '$insertedIp', $updatedBy, '$updatedIp', '$sp_type')");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


		return DB::select('CALL sp_emp_setup_loan_insertupdate(
			?,
			"'. $request->vtypeid .'",
			"'. $request->empid .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->vdate)) .'",
			"'. $request->bankid .'",
			"'. $request->chequeno .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->chequedate)) .'",
			"'. $request->amount .'",
			"'. $request->installment .'",
			"'. $request->remarks .'",
			'.  $companyid .',
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
