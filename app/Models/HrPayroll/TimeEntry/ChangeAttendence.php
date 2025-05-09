<?php

namespace App\Models\HrPayroll\TimeEntry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class ChangeAttendence extends Model
{
	use HasFactory;
	protected $table = 'att_attendance';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/time-entry/change-attendence';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getEmpChangeAttendence($request, $id, $type) {

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

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}
		$datein = date('Y-m-d 00:00:00', strtotime($request->vdate));
		$dateout = date('Y-m-d 00:00:00', strtotime($request->vdate . ' +1 day'));
		$employeeid = $request->employeeid;
		$deptid = $request->get('deptid',-1)??-1;
		$etypeid = $request->get('etypeId',0)??0;


		$ChangeAttendence = ChangeAttendence::hydrate(
			DB::select('CALL sp_att_timeentry_change_attendance_get('. $id .', '. $employeeid .', "'. $datein .'",  "'. $dateout .'" , '. $deptid .', '. $etypeid .', '. $userid .', '. $companyid .', '. $locationid .')')
		);

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Change Attendence",
						"ErrorMsg"=>"CALL sp_att_timeentry_change_attendance_get($id,$employeeid,'$datein','$dateout',$deptid,$etypeid,$userid,$companyid,$locationid)");

	
		$this->utilsModel->saveDbLogs($logData);
		/* Logs for stored procedure ends */

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'ChangeAttendence' => $ChangeAttendence,
				'status' => 'success'
			]);

		} else {

			return $ChangeAttendence;
		}

	}

	public function createAttendanceEmployee($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Employee Attendance created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating employee attendance.', $type, $this->PAGE_LINK);
		}
	}

	public function updateAttendanceEmployee($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Employee Attendance Changed successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'employeeid'	  => 'required ',
			// 'vdate'	  => 'required ',
			'companyid'   => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			// 'isactive'		  => 'required | integer',
			'insertedby'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'	  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'	  => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
		]);
	}

	public function createUpdate($request, $id, $sp_type, $type) {
		//dd($request->date." ".$request->datein);
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
			$updatedBy = Auth::id();
			$updatedIp = $request->ip();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}
		$datein = date('Y-m-d H:i:s', strtotime("$request->datein"));
		$dateout = date('Y-m-d H:i:s', strtotime("$request->dateout"));
		$remarks = isset($request->remarks) ? $request->remarks : '-';

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Change Attendence",
						"ErrorMsg"=>"CALL sp_att_timeentry_change_attendance_insertupdate(@id,$request->employeeid,'$datein','$dateout','$remarks',0,$companyid,$locationid,$insertedBy,'$insertedIp',$updatedBy,'$updatedIp','$sp_type')");

	
		$this->utilsModel->saveDbLogs($logData);
		/* Logs for stored procedure ends */

		return DB::select('CALL sp_att_timeentry_change_attendance_insertupdate(
			?,
			"'. $request->employeeid .'",
			"'. $datein .'",
			"'. $dateout .'",
			"'. $remarks .'",
			0,
			'.  $companyid .',
			'.  $locationid .',
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
