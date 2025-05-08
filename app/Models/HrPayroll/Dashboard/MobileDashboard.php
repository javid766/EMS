<?php

namespace App\Models\HrPayroll\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Utils;

class MobileDashboard extends Model
{
	use HasFactory;

	public $utilsModel;

	public $PAGE_LINK = 'dashboard';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getAttendanceActivity($request, $type) {

		$validated = $this->validateParams($request, $type);

		if ($validated == "true") {

			$attendanceEntry = $this->callStoredProcedure('sp_dashboard_att_activity_mbl', $request, $type);

			return $attendanceEntry;

		} else {

			return $validated;
		}
	}

	public function getLeaveBalance($request, $type) {

		$validated = $this->validateParams($request, $type);

		if ($validated == "true") {

			$leaveBalance = $this->callStoredProcedure('sp_dashboard_att_leave_balance_emp', $request, $type);

			return $leaveBalance;

		} else {

			return $validated;
		}
	}

	public function getAttendanceActivityEmp($request, $type) {

		$validated = $this->validateParams($request, $type);

		if ($validated == "true") {

			$monthlyDetail = $this->callStoredProcedure('sp_dashboard_att_activity_emp', $request, $type);

			return $monthlyDetail;

		} else {

			return $validated;
		}
	}

	public function validateParams($request, $type) {

		$validator = Validator::make($request->all() ,[
			'datein' => 'required',
			'dateout' => 'required',
			'employeeid' => 'required',
			'userid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'companyid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
		]);

		if($validator->fails()) {

			if ($type == $this->utilsModel->CALL_TYPE_API) {

				return array(
					'status' => 'error', 
					'message' => $validator->messages()->first()
				);
			}

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		return "true";
	}

	public function callStoredProcedure($procedureName, $request, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$userid = $request->userid;
			$employeeid = $request->employeeid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;

		} else {

			$userid = Auth::id();
			$employeeid = $request->session()->get('employeeid', 0);
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}

		$datein = date('Y-m-d', strtotime($request->datein));
		$dateout = date('Y-m-d', strtotime($request->dateout));

		$resultData = self::hydrate(DB::select('CALL '. $procedureName .'('. $employeeid .', "'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .', '. $locationid .')'));

		return $resultData;
	}
}
