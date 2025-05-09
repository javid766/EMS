<?php

namespace App\Models\HrPayroll\TimeEntry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Utils;
use Auth;

class RosterEntry extends Model
{
	use HasFactory;
	protected $table = 'att_timentry_roster_entry';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/time-entry/roster-entry';

	public function __construct() {
		$this->utilsModel = new Utils();
	}

	public function getRosterEntries($request, $id, $type) {
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
			$dateout = date('Y-m-d 00:00:00', strtotime($request->dateout));
		    $employeeid = $request->employeeid;
		    $deptid = $request->get('deptid',-1)??-1;


		$rosterEntry = RosterEntry::hydrate(
			DB::select('CALL sp_att_timeentry_roster_entry_get('. $id .', '. $employeeid .', "'. $datein .'",  "'. $dateout .'" ,'. $deptid .','. $userid .', '. $companyid .', '. $locationid .')')
		);
		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Roster Entry",
						"ErrorMsg"=>"CALL sp_att_timeentry_roster_entry_get($id, $employeeid,'$datein','$dateout',$deptid,$userid,$companyid,$locationid)");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


		if ($type == $this->utilsModel->CALL_TYPE_API) {
			return response([
				'rosterEntry' => $rosterEntry,
				'status' => 'success'
			]);
		} else {
			return $rosterEntry;
		}
	}

	public function createRosterEntry($request, $type) {
		$validator = $this->validateCreateUpdateParams($request, $type);
		if($validator->fails()) {
			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}
		$id = 0;
		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {
			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Records Saved successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {
			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error while saving Records.', $type, $this->PAGE_LINK);
		}
	}

	public function updateRosterEntry($request, $id, $type) {
		$validator = $this->validateCreateUpdateParams($request, $type);
		if($validator->fails()) {
			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}
		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);
		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Records updated successfully', 'id', $id, $type, $this->PAGE_LINK);
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

		$vdate = date('Y-m-d 00:00:00', strtotime($request->vdate));

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Roster Entry",
						"ErrorMsg"=>"CALL sp_att_timeentry_roster_entry_insertupdate(@id,$request->employeeid,'$vdate',strtoupper($request->d01),strtoupper($request->d02),strtoupper($request->d03),strtoupper($request->d04),strtoupper($request->d05),strtoupper($request->d06),strtoupper($request->d07),strtoupper($request->d08),strtoupper($request->d09),strtoupper($request->d10),strtoupper($request->d11),strtoupper($request->d12),strtoupper($request->d13),strtoupper($request->d14),strtoupper($request->d15),strtoupper($request->d16),strtoupper($request->d17),strtoupper($request->d18),strtoupper($request->d19),strtoupper($request->d20),strtoupper($request->d21),strtoupper($request->d22),strtoupper($request->d23),strtoupper($request->d24),strtoupper($request->d25),strtoupper($request->d26),strtoupper($request->d27),strtoupper($request->d28),strtoupper($request->d29),strtoupper($request->d30),strtoupper($request->d31),$companyid,$locationid,$insertedBy,'$insertedIp',$updatedBy,'$updatedIp','$sp_type')");

	
		$this->utilsModel->saveDbLogs($logData);
		/* Logs for stored procedure ends */

		return DB::select('CALL sp_att_timeentry_roster_entry_insertupdate(
			?,
			'. $request->employeeid .',
			"'. $vdate .'",
			"'. strtoupper($request->d01) .'",
			"'. strtoupper($request->d02) .'",
			"'. strtoupper($request->d03) .'",
			"'. strtoupper($request->d04) .'",
			"'. strtoupper($request->d05) .'",
			"'. strtoupper($request->d06) .'",
			"'. strtoupper($request->d07) .'",
			"'. strtoupper($request->d08) .'",
			"'. strtoupper($request->d09) .'",
			"'. strtoupper($request->d10) .'",
			"'. strtoupper($request->d11) .'",
			"'. strtoupper($request->d12) .'",
			"'. strtoupper($request->d13) .'",
			"'. strtoupper($request->d14) .'",
			"'. strtoupper($request->d15) .'",
			"'. strtoupper($request->d16) .'",
			"'. strtoupper($request->d17) .'",
			"'. strtoupper($request->d18) .'",
			"'. strtoupper($request->d19) .'",
			"'. strtoupper($request->d20) .'",
			"'. strtoupper($request->d21) .'",
			"'. strtoupper($request->d22) .'",
			"'. strtoupper($request->d23) .'",
			"'. strtoupper($request->d24) .'",
			"'. strtoupper($request->d25) .'",
			"'. strtoupper($request->d26) .'",
			"'. strtoupper($request->d27) .'",
			"'. strtoupper($request->d28) .'",
			"'. strtoupper($request->d29) .'",
			"'. strtoupper($request->d30) .'",
			"'. strtoupper($request->d31) .'",
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
