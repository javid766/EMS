<?php

namespace App\Models\HrPayroll\TimeEntry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Utils;
use Auth;

class OTEntryMonthly extends Model
{
	use HasFactory;
	protected $table = 'att_timeentry_ot_entry_monthly';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/time-entry/ot-entry-monthly';

	public function __construct() {
		$this->utilsModel = new Utils();
	}

	public function getOTEntriesMonthly($request, $id, $type) {
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
		$datein = date('Y-m-d 00:00:00', strtotime($request->datein));
		$dateout = date('Y-m-d 00:00:00', strtotime($request->dateout));
		$employeeid = $request->employeeid;
		$deptid = $request->get('deptid',-1)??-1;

		$otEntryMonthly = OTEntryMonthly::hydrate(
			DB::select('CALL sp_att_timeentry_ot_entry_monthly_get('. $id .', '. $employeeid .', "'. $datein .'",  "'. $dateout .'" ,'. $deptid .','. $userid .', '. $companyid .', '. $locationid .')')
		);

		/* Logs for stored procedure starts */

		$logData = array('LogName'=>"O/T Entry Monthly",
						"ErrorMsg"=>"CALL sp_att_timeentry_ot_entry_monthly_get($id,$employeeid,'$datein','$dateout',$deptid,$userid,$companyid,$locationid)");

	
		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


		if ($type == $this->utilsModel->CALL_TYPE_API) {
			return response([
				'month_days_attendance' => $otEntryMonthly,
				'status' => 'success'
			]);
		} else {
			return $otEntryMonthly;
		}
	}
	public function createOTEntryMonthly($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'OT Monthly created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating OT Monthly.', $type, $this->PAGE_LINK);
		}
	}
	public function updateOTEntryMonthly($request, $id, $type) {
		$validator = $this->validateCreateUpdateParams($request, $type);
		if($validator->fails()) {
			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);
		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'OT Monthly Records updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			// 'deptid'=> 'required  | integer',
			'vdate'=> 'required ',
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

		if ($request->overtime == null){
			$overtime = 0.00;
		}
		else{
			$overtime = $request->overtime;
		}

		$vdate = date('Y-m-d 00:00:00', strtotime($request->vdate));
		$remarks = (isset($request->remarks) ? $request->remarks : '-');

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"O/T Entry Monthly",
						"ErrorMsg"=>"CALL sp_att_timeentry_ot_entry_monthly_insertupdate(@id,$request->employeeid,'$vdate','$overtime','$remarks',1,$companyid,$locationid,$insertedBy,'$insertedIp',$updatedBy,'$updatedIp','$sp_type')");

	
		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */

		return DB::select('CALL sp_att_timeentry_ot_entry_monthly_insertupdate(
			?,
			'. $request->employeeid .',
			"'. $vdate .'",
			'. $overtime .',
			"'. $remarks .'",
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
