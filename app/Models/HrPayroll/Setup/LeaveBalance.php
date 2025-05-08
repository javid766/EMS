<?php

namespace App\Models\HrPayroll\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class LeaveBalance extends Model
{
    use HasFactory;
    protected $table = 'att_setup_leave_balance';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/setup/leave-balance';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getLeaveBalances($request, $id, $type) {

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

		// if ($companyid == 0) {

		// 	return $this->utilsModel->returnResponseStatusMessage('error', "Session expired please login again", $type, '/login');
		// }

		$leaveBalances = LeaveBalance::hydrate(
			DB::select('CALL sp_att_setup_leave_balance_get('. $id .', '. $userid .', '. $companyid .', '. $locationid .')')
		);

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Leave Balance", "ErrorMsg"=>"CALL sp_att_setup_leave_balance_get($id,$userid,$companyid,$locationid)");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'leave_balances' => $leaveBalances,
				'status' => 'success'
			]);

		} else {

			return $leaveBalances;
		}

	}

	public function createLeaveBalance($request, $type) {


		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Leave Balance created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating leave balance.', $type, $this->PAGE_LINK);
		}
	}

	public function updateLeaveBalance($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Leave Balance updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteLeaveBalance($id, $type) {

		if ($id > 0) {

			$action_type = $this->utilsModel->SP_ACTION_DELETE;

			$result = DB::select('CALL sp_att_setup_leave_balance_insertupdate(
				?, 0, 0, 0, NOW(), NOW(), 0, 0, 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Leave Balance", "ErrorMsg"=>"SET @id = $id; CALL sp_att_setup_leave_balance_insertupdate(@id, 0, 0, 0, NOW(), NOW(), 0, 0, 0, 0, 0, 0, 0, 0, '$action_type')");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */

			return $this->utilsModel->returnResponseStatusMessage('success', 'Leave Balance deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Leave Balance id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'vcode'		 => 'required',
			'vname'		 => 'required',
			'attgroupid' => 'required | integer',
			'datein' 	 => 'required',
			'dateout' 	 => 'required',
			'leavelimit' => 'required | integer',
			'companyid'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			// 'isactive'		  => 'required | integer',
			'insertedby' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip' => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'	 => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'	 => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
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

		$vname = trim($request->vname);
		$is_active = (isset($request->isactive) ? $request->isactive : 0);
		$datein = date('Y-m-d H:m:s', strtotime($request->datein));
		$dateout = date('Y-m-d H:m:s', strtotime($request->dateout));


		if($sp_type == 'u'){
			$set_id = "SET @id = $id;";
		}else{
			$set_id = "";
		}

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Leave Balance", "ErrorMsg"=>"$set_id CALL sp_att_setup_leave_balance_insertupdate(@id, '$request->vcode', '$vname', $request->attgroupid, '$datein', '$dateout', $request->leavelimit, $companyid, $locationid,  $is_active, $insertedBy, '$insertedIp', $updatedBy, '$updatedIp', '$sp_type')");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


		return DB::select('CALL sp_att_setup_leave_balance_insertupdate(
			?,
			"'. $request->vcode .'",
			"'. $vname .'",
			'. $request->attgroupid .',
			"'. $datein .'",
			"'. $dateout .'",
			'. $request->leavelimit .',
			'. $companyid .',
			'. $locationid .',
			'. $is_active .',
			'.  $insertedBy  .',
			"'. $insertedIp .'",
			'. $updatedBy .',
			"'. $updatedIp .'",
			"'. $sp_type .'")',
			[
				$id
			]
		)[0]->id;
	}
}
