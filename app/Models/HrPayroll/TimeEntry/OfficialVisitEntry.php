<?php

namespace App\Models\HrPayroll\TimeEntry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class OfficialVisitEntry extends Model
{
    use HasFactory;
    protected $table = 'att_timeentry_official_visit_entry';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/time-entry/official-visit-entry';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getOfficialVisitEntries($request, $id, $type) {

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

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
		}

		$officialVisitEntry = OfficialVisitEntry::hydrate(
			DB::select('CALL sp_att_timeentry_official_visit_entry_get('. $id .', '. $userid .', '. $companyid .')')
		);

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Official Visit Entry",
						"ErrorMsg"=>"CALL sp_att_timeentry_official_visit_entry_get($id,$userid,$companyid)");

	
		$this->utilsModel->saveDbLogs($logData);
		/* Logs for stored procedure starts */

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'OfficialVisitEntry' => $officialVisitEntry,
				'status' => 'success'
			]);

		} else {

			return $officialVisitEntry;
		}

	}

	public function createOfficialVisitEntry($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Official Visit Entry created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating Official Visit Entry.', $type, $this->PAGE_LINK);
		}
	}

	public function updateOfficialVisitEntry($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Official Visit Entry updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteOfficialVisitEntry($id, $type) {

		

		if ($id > 0) {

			$result = DB::select('CALL sp_att_timeentry_official_visit_entry_insertupdate(
				?, 0, 0, NOW(), NOW(), 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);
			/* Logs for stored procedure starts */
			$action_type = $this->utilsModel->SP_ACTION_DELETE;
			$logData = array('LogName'=>"Official Visit Entry",
						"ErrorMsg"=>"CALL sp_att_timeentry_official_visit_entry_insertupdate(
							$id, 0, NOW(), NOW(), 0, 0, 0, 0, 0, 0,'$action_type')");

	
			$this->utilsModel->saveDbLogs($logData);
			/* Logs for stored procedure starts */

			return $this->utilsModel->returnResponseStatusMessage('success', 'Official Visit Entry deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Official Visit Entry id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'empid'	  => 'required ',
			'appno'	  => 'required ',
			'vdatefrom'	  => 'required ',
			'vdateto'	  => 'required ',
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

		} else {

			$insertedBy = Auth::id();
			$insertedIp = $request->ip();
			$updatedBy = Auth::id();
			$updatedIp = $request->ip();
			$companyid = $request->session()->get('companyid', 0);
		}

		if($sp_type == 'u'){
			$set_id = "SET @id = $id;";
		}else{
			$set_id = "";
		}
		

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Official Visit Entry",
						"ErrorMsg"=>"$set_id CALL sp_att_timeentry_official_visit_entry_insertupdate(@id,$request->empid,'$request->appno','$request->vdatefrom','$request->vdateto','$request->remarks',$companyid,$insertedBy,'$insertedIp',$updatedBy,'$updatedIp','$sp_type')");

	
		$this->utilsModel->saveDbLogs($logData);
		/* Logs for stored procedure ends */

		return DB::select('CALL sp_att_timeentry_official_visit_entry_insertupdate(
			?,
			"'. $request->empid .'",
			"'. $request->appno .'",
			"'. $request->vdatefrom .'",
			"'. $request->vdateto  .'",
			"'. $request->remarks .'",
			'.  $companyid .',
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
