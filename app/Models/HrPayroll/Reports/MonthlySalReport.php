<?php

namespace App\Models\HrPayroll\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Utils;

class MonthlySalReport extends Model
{
	use HasFactory;

	public $utilsModel;

	public $PAGE_LINK = '/reports/monthy-salary-report';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getMonthlySal($request, $id, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {
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
		$attfilter = $request->attfilter;
		$attMonthlySalData = '';
		if ($attfilter == 'salsheetcash' || $attfilter == 'salsheetcheque' || $attfilter == 'salsheetcomplete' || $attfilter == 'finalsettlement') {
			 $attMonthlySalData = DB::select('CALL sp_rpt_sal_sheet('. $userid .', '. $companyid .','. $locationid .' , \''.$request->cwhere .' \',"'. $request->datefrom .'","'. $request->dateto .'")');
		}

		if($attfilter == 'salarysummarycash'|| $attfilter == 'salarysummarycheque' || $attfilter == 'summarycomplete' || $attfilter == 'finalsettlementsummary'){
			 $attMonthlySalData = DB::select('CALL sp_rpt_sal_summary('. $userid .', '. $companyid .','. $locationid .' ,"'. $request->datefrom .'",  \''.$request->cwhere .' \',"'. $request->vtype .'")');
		}

		if ($attfilter == 'empsalaryhistory') {
			 $attMonthlySalData = DB::select('CALL sp_rpt_att_sal_history('. $userid .', '. $companyid .','. $locationid .' ,"'. $request->datefrom .'",  \''.$request->cwhere .' \')');
		}

		if ($attfilter == 'advancedeductionsheet') {
			 $attMonthlySalData = DB::select('CALL sp_rpt_att_sal_advance_deduction_summary('. $userid .', '. $companyid .','. $locationid .' ,"'. $request->datefrom .'",  \''.$request->cwhere .' \',"sheet")');
		}
		if ($attfilter == 'advancedeductionsummary') {
			$attMonthlySalData = DB::select('CALL sp_rpt_att_sal_advance_deduction_summary('. $userid .', '. $companyid .','. $locationid .' ,"'. $request->datefrom .'",  \''.$request->cwhere .' \',"summary")');
		}
		if ($type == $this->utilsModel->CALL_TYPE_API) {
			return response([
				'AttMonthlyReport' => $attMonthlySalData,
				'status' => 'success'
			]);

		} else {

			return $attMonthlySalData;
		}

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

		$salarySlip = self::hydrate(DB::select('CALL sp_rpt_att_sal_slip('. $userid .', '. $companyid .', '. $locationid .','. $employeeid .', "'. $request->vdate .'", \''.$request->cwhere .' \' )')
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