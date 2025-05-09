<?php

namespace App\Models\HrPayroll\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Utils;
use Auth;

class SalLoanDeduct extends Model
{
	use HasFactory;
	protected $table = 'att_sal_loan_deduct';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/employee/loan-deduction';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getSalLoanDeducts($request, $id, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				'companyid' => 'required | integer',
				'locationid' => 'required | integer',
				'employeeid' => 'required | integer',
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
				'datein' => 'required' ,
				'dateout' => 'required' ,
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
		$salLoanDeduct = SalLoan::hydrate(
			DB::select('CALL sp_att_sal_loan_deduct_get('. $id .', '. $employeeid .', "'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .', '. $locationid .')')
		);

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'sal_loan_deduct' => $salLoanDeduct,
				'status' => 'success'
			]);

		} else {
			foreach ($salLoanDeduct as $key => $value) {
				$loanamount=$value->loanamount;
				$installment=$value->installment;
				$value['loanamount']=intval($loanamount);
				$value['installment']=intval($installment);
			}
			return $salLoanDeduct;
		}
	}

	public function createSalLoanDeduct($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Loan Deduction record created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating loan deduction.', $type, $this->PAGE_LINK);
		}
	}

	public function updateSalLoanDeduct($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Loan Deduction record updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteSalLoanDeduct($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_att_sal_loan_deduct_insertupdate(
				?, 0, 0, 0, NOW(), 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			return $this->utilsModel->returnResponseStatusMessage('success', 'Loan Deduction record deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Loan Deduction id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {
		return Validator::make($request->all() ,[
			'loanid'=> 'required  | integer',
			'employeeid'=> 'required  | integer',
			'amount'=> 'required ',
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
		
		return DB::select('CALL sp_att_sal_loan_deduct_insertupdate(
			?,
			"'. $request->loanvno .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->datein)) .'",
			'. $request->loanid .',
			'. $request->employeeid .',
			"'. $request->refno .'",
			'. $request->amount .',
			"'. $request->remarks .'",
			1,
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
