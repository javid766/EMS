<?php

namespace App\Models\HrPayroll\TimeEntry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class LeaveApply extends Model
{
    use HasFactory;
    protected $table = 'att_timeentry_leave_apply';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/time-entry/leave-entry';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getLeaveEntries($request, $id, $type) {

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
			$locationid = $request->locationid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}

		$datein = date('Y-m', strtotime($request->datein));
		$datein = date('Y-m-d', strtotime($datein));
		$last_date_find = strtotime(date("Y-m-d", strtotime($datein)) . ", last day of this month");
        $dateout = date("Y-m-d",$last_date_find);
		$leaveApply = LeaveApply::hydrate(
			DB::select('CALL sp_att_timeentry_leave_apply_get('. $id .', '. $request->employeeid .', "'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .','. $locationid .')')
		);

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Leave Apply",
						"ErrorMsg"=>"CALL sp_att_timeentry_leave_apply_get($id,$request->employeeid,'$datein','$dateout',$userid,$companyid,$locationid)");

	
		$this->utilsModel->saveDbLogs($logData);
		/* Logs for stored procedure ends */

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'LeaveApply' => $leaveApply,
				'status' => 'success'
			]);

		} else {

			return $leaveApply;
		}

	}

	public function createLeaveEntry($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Leave Entry created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating Leave Entry.', $type, $this->PAGE_LINK);
		}
	}

	public function updateLeaveEntry($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Leave Entry updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteLeaveEntry($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_att_timeentry_leave_apply_insertupdate(
				?, 0, 0, NOW(), NOW(), 0, 0, 0, 0, 0, 0,  0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);
			/* Logs for stored procedure starts */
			$action_type = $this->utilsModel->SP_ACTION_DELETE;
			$logData = array('LogName'=>"Leave Apply",
						"ErrorMsg"=>"CALL sp_att_timeentry_leave_apply_insertupdate(
							@id, 0, NOW(), NOW(), 0, 0, 0, 0, 0, 0,0, 0,'$action_type')");

	
			$this->utilsModel->saveDbLogs($logData);
			/* Logs for stored procedure ends */

			return $this->utilsModel->returnResponseStatusMessage('success', 'Leave Entry deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Leave Entry id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'employeeid'	  => 'required ',
			'leavetypeid'	  => 'required ',
			'datein'	  => 'required ',
			'dateout'	  => 'required ',
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
            $locationid = $request->locationid;

		} else {

			$insertedBy = Auth::id();
			$insertedIp = $request->ip();
			$updatedBy = Auth::id();
			$updatedIp = $request->ip();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}

		$datein = date('Y-m-d 00:00:00', strtotime($request->datein));
		$dateout = date('Y-m-d 00:00:00', strtotime($request->dateout));
		$remarks = isset($request->remarks) ? $request->remarks : '-';

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Leave Apply",
						"ErrorMsg"=>"CALL sp_att_timeentry_leave_apply_insertupdate(@id,$request->leavetypeid,$request->employeeid,'$datein','$dateout','$remarks',0,$companyid,$locationid,$insertedBy,'$insertedIp',$updatedBy,'$updatedIp','$sp_type')");

	
		$this->utilsModel->saveDbLogs($logData);
		/* Logs for stored procedure ends */

		return DB::select('CALL sp_att_timeentry_leave_apply_insertupdate(
			?,
			'. $request->leavetypeid .',
			'. $request->employeeid .',
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

	public function getLeaveApproval($request, $id, $type) {

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

		$datein = date('Y-m', strtotime($request->datein));
		$datein = date('Y-m-d', strtotime($datein));
		$last_date_find = strtotime(date("Y-m-d", strtotime($datein)) . ", last day of this month");
        $dateout = date("Y-m-d",$last_date_find);

		$leaveApply = LeaveApply::hydrate(
			DB::select('CALL sp_att_timeentry_leave_approval_get('. $id .', '. $request->employeeid .', "'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .','. $locationid .')')
		);
		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Leave Apply",
						"ErrorMsg"=>"CALL sp_att_timeentry_leave_approval_get($id,$request->employeeid,'$datein','$dateout',$userid,$companyid,$locationid)");

	
		$this->utilsModel->saveDbLogs($logData);
		/* Logs for stored procedure ends */

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'LeaveApply' => $leaveApply,
				'status' => 'success'
			]);

		} else {

			return $leaveApply;
		}
	}

	public function updateApproval($request, $type) {

		$validator = Validator::make($request->all() ,[
			'inIds'	  => 'required ',
			'leaveStatus' => 'required ',
			'userid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'companyid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
		]);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}

		if($type == 'u'){
			$set_id = "SET @id = $id;";
		}else{
			$set_id = "";
		}
		

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Leave Apply", "ErrorMsg"=>"$set_id CALL sp_att_timeentry_leave_approval_update('$request->inIds',$request->leaveStatus,$userid,$companyid,$locationid)");

	
		$this->utilsModel->saveDbLogs($logData);
		/* Logs for stored procedure ends */

		$result = DB::select('CALL sp_att_timeentry_leave_approval_update("'. $request->inIds .'", '. $request->leaveStatus .', '.  $userid .', '.  $companyid .', '.  $locationid .')'		)[0];

		if ($result->status == 'success') {

			return $this->utilsModel->returnResponseStatusMessage('success', 'Leave Approval status updated successfully', $type, '/time-entry/leave-approval');
		}

		return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error', $type, '/time-entry/leave-approval');
	}
}
