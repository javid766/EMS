<?php

namespace App\Models\HrPayroll\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Utils;
use Auth;

class ClosingMonthCheque extends Model
{
    use HasFactory;
    protected $table = 'att_closing_month_cheque';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/employee/closing-month-cheque';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getClosingMonthCheques($request, $id, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				'companyid' => 'required | integer',
				'locationid' => 'required | integer',
				'datein' => 'required' ,
				'dateout' => 'required' ,
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
				'datein' => 'required' ,
				'dateout' => 'required' ,
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
		$datein = date('Y-m-d 00:00:00', strtotime($request->datein));
		$dateout = date('Y-m-d 00:00:00', strtotime($request->dateout));

		$closingMonthCheque = ClosingMonthCheque::hydrate(
			DB::select('CALL sp_att_closing_month_cheque_get('. $id .', '. $employeeid .', "'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .', '. $locationid .')')
		);

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'closing_month_cheque' => $closingMonthCheque,
				'status' => 'success'
			]);

		} else {

			return $closingMonthCheque;
		}
	}

	public function createClosingMonthCheque($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$result = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($result->STATUS == "success") {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', "Closing month cheque saved successfully", 'id', $result->id, $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', $result->message, $type, $this->PAGE_LINK);
		}
	}

	public function updateClosingMonthCheque($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$result = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Closing month cheque updated successfully', 'id', $result->id, $type, $this->PAGE_LINK);
	}

	public function deleteClosingMonthCheque($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_att_closing_month_cheque_insertupdate(
				?, 0, 0, NOW(), 0, 0, NOW(), 0, 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			return $this->utilsModel->returnResponseStatusMessage('success', 'Closing month cheque deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Closing month cheque id is required", $type, $this->PAGE_LINK);
		}
	}


	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'bankid' => 'required | integer',
			'vdate' => 'required',
			'employeeid' => 'required | integer',
			'chequeno' => 'required',
			'chequedate' => 'required',
			'chequeamount' => 'required',
			'companyid'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedby' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip' => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip' => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			
		]);
	}

	public function createUpdate($request, $id, $sp_type, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {
	
			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;
			$insertedBy = $request->insertedby;
			$insertedIp = $request->insertedip;
			$updatedBy = $request->updatedby;
			$updatedIp = $request->updatedip;

		} else {
	
			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
			$insertedBy = Auth::id();
			$insertedIp = $request->ip();
			$updatedBy  = Auth::id();
			$updatedIp  = $request->ip();
		}

		return DB::select('CALL sp_att_closing_month_cheque_insertupdate(?,
			"'. $request->employeeid. '",
			'. $request->vtype. ',
			"'. date('Y-m-d H:m:s', strtotime($request->vdate)) .'",
			"'. $request->bankid. '",
			"'. $request->chequeno. '",
			"'. date('Y-m-d H:m:s', strtotime($request->chequedate)) .'",
			"'. $request->chequeamount. '",
			"'. $request->remarks. '",
			'.  $companyid .',
			'.  $insertedBy  .',
			"'. $insertedIp .'",
			'.  $updatedBy  .',
			"'. $updatedIp .'",
			"'. $sp_type .'")', 
			[$id])[0];
	}
}
