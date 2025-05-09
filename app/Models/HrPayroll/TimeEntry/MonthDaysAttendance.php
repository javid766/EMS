<?php

namespace App\Models\HrPayroll\TimeEntry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Utils;
use Auth;

class MonthDaysAttendance extends Model
{
	use HasFactory;
	protected $table = 'att_timeentry_month_days_attendance';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/time-entry/month-days';

	public function __construct() {
		$this->utilsModel = new Utils();
	}

	public function getMonthDaysAttendance($request, $id, $type) {
		if ($type == $this->utilsModel->CALL_TYPE_API) {
			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				'companyid' => 'required | integer',
				'locationid' => 'required | integer',
			]);
			if($validator->fails()) {
				return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
			}

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;	

		} else {
			// $validator = Validator::make($request->all() ,[
			// 	'vdate' => 'required' ,
			// 	'deptid' => 'required' ,
			// ]);

			// if($validator->fails()) {
			// 	return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
			// }

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}
		$datein = date('Y-m-d 00:00:00', strtotime($request->datein));
		$dateout = date('Y-m-d 00:00:00', strtotime($request->dateout));
	    $employeeid = $request->employeeid;
	    $deptid = $request->get('deptid',-1)??-1;

		$monthDaysAttendance = MonthDaysAttendance::hydrate(
			DB::select('CALL sp_att_timeentry_month_days_attendance_get('. $id .', '. $employeeid .', "'. $datein .'",  "'. $dateout .'" , '. $deptid .','. $userid .', '. $companyid .', '. $locationid .')')
		);

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Month Attendance Days",
						"ErrorMsg"=>"CALL sp_att_timeentry_month_days_attendance_get($id,$employeeid,'$datein','$dateout',$deptid,$userid,$companyid,$locationid)");

	
		$this->utilsModel->saveDbLogs($logData);
		/* Logs for stored procedure ends */

		if ($type == $this->utilsModel->CALL_TYPE_API) {
			return response([
				'month_days_attendance' => $monthDaysAttendance,
				'status' => 'success'
			]);
		} else {
			return $monthDaysAttendance;
		}
	}

	public function createMonthDaysAttendance($request, $type) {
		$validator = $this->validateCreateUpdateParams($request, $type);
		if($validator->fails()) {
			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}
		$id = 0;
		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {
			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Month Attendance days Records Saved successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {
			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error while saving Records.', $type, $this->PAGE_LINK);
		}
	}

	public function updateMonthDaysAttendance($request, $id, $type) {
		$validator = $this->validateCreateUpdateParams($request, $type);
		if($validator->fails()) {
			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}
//dd($request->all());

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);
		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Month Attendance days Records updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			// 'deptid'=> 'required  | integer',
			'datein'=> 'required ',
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

		$datein = date('Y-m-d 00:00:00', strtotime($request->datein));
		$dateout = (isset($request->attdays) ? $request->attdays : '00.00');
		$remarks = isset($request->remarks) ? $request->remarks : '-';

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Month Attendance Days",
						"ErrorMsg"=>"CALL sp_att_timeentry_month_days_attendance_insertupdate(@id,$request->employeeid,'$datein','$dateout','$remarks',$companyid,$locationid,$insertedBy,'$insertedIp',$updatedBy,'$updatedIp','$sp_type')");

	
		$this->utilsModel->saveDbLogs($logData);
		/* Logs for stored procedure ends */

		return DB::select('CALL sp_att_timeentry_month_days_attendance_insertupdate(
			?,
			'. $request->employeeid .',
			"'. $datein .'",
			'.  $dateout .',
			"'. $remarks .'",
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
