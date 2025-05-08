<?php

namespace App\Models\HrPayroll\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Utils;

class SalarySlip extends Model
{
	use HasFactory;

	public $utilsModel;

	public $PAGE_LINK = '';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getSalarySlip($request, $employeeid, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API || $type == $this->utilsModel->CALL_TYPE_API_PDF) {

			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				'companyid' => 'required | integer',
				'locationid' => 'required | integer'
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

		$datein = date('Y-m-d', strtotime($request->datein));
		$dateout = date('Y-m-d', strtotime($request->dateout));

		$salarySlip = self::hydrate(DB::select('CALL sp_rpt_att_salary_slip('. $employeeid .', "'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .', '. $locationid .')')
		);

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'salaryslip' => $salarySlip,
				'status' => 'success'
			]);

		} else {

			return $salarySlip;
		}
	}
}