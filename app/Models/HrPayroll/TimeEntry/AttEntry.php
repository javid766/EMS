<?php

namespace App\Models\HrPayroll\TimeEntry;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class AttEntry extends Model
{
	use HasFactory;
	protected $table = 'att_attendance';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK ;

	public function __construct() {
        $this->PAGE_LINK = route('time-entry-attentry');  
		$this->utilsModel = new Utils();
	}

	public function getAttEntries($request, $id, $type) {
		
		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				'companyid' => 'required | integer',
				'locationid' => 'required | integer',
				'date' =>'required',
				'employeeid' => 'required' ,

			]);

			if($validator->fails()) {

				return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
			}

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;
			$datein = date('Y-m-d 00:00:00', strtotime($request->date));
			$dateout = date('Y-m-d 00:00:00', strtotime($request->date . ' +1 day'));

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
			$datein = date('Y-m-d 00:00:00', strtotime($request->datein));
			$dateout = date('Y-m-d 00:00:00', strtotime($request->dateout));
		}

		$deptid = $request->get('deptid',-1)??-1;
		$etypeid = $request->get('etypeId',0)??0;

		$AttEntry = AttEntry::hydrate(
			DB::select('CALL sp_att_timeentry_attendance_get('. $id .', '. $request->employeeid .', "'. $datein .'", "'. $dateout .'", '. $deptid .', '. $etypeid .', '. $userid .', '. $companyid .', '. $locationid .' , '. $request->inout .')')
		);

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Attendance Entry",
						"ErrorMsg"=>"CALL sp_att_timeentry_attendance_get($id,$request->employeeid,'$datein','$dateout',$deptid,$etypeid,$userid,$companyid,$locationid,$request->inout)");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'AttEntry' => $AttEntry,
				'status' => 'success'
			]);

		} else {

			return $AttEntry;
		}

	}

	public function createAttEntry($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Attendance Entry created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating Attendance Entry.', $type, $this->PAGE_LINK);
		}
	}

	public function updateAttEntry($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		


		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Attendance Entry updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteAttEntry($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_att_timeentry_attendance_insertupdate(
				?, 0, NOW(), NOW(), 0, 0, 0, 0, 0, 0,0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

		/* Logs for stored procedure starts */

		$action_type = $this->utilsModel->SP_ACTION_DELETE;
		$logData = array('LogName'=>"Attendance Entry",
						"ErrorMsg"=>"CALL sp_att_timeentry_attendance_insertupdate(
							@id, 0, NOW(), NOW(), 0, 0, 0, 0, 0, 0,0, 0, 0, '$action_type')");
 
		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


			return $this->utilsModel->returnResponseStatusMessage('success', 'Attendance Entry deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Attendance Entry id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'employeeid'  => 'required ',
			'date'		 => 'required',
			// 'datein'	  => 'required ',
			// 'dateout'	  => 'required ',
			'companyid'   => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
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

		$datein = date('Y-m-d H:i:s', strtotime("$request->datein"));
		$dateout = date('Y-m-d H:i:s', strtotime("$request->dateout"));
		$clear = (isset($request->isclear) ? $request->isclear : 0);
		$remarks = isset($request->remarks) ? $request->remarks : '-';

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Attendance Entry",
						"ErrorMsg"=>"CALL sp_att_timeentry_attendance_insertupdate(@id,$request->employeeid,'$datein','$dateout',$request->shiftid,'$remarks',$clear,$companyid,$locationid,$insertedBy,'$insertedIp',$updatedBy,'$updatedIp','$sp_type')");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


		return DB::select('CALL sp_att_timeentry_attendance_insertupdate(
			?,
			'. $request->employeeid .',
			"'. $datein .'",
			"'. $dateout .'",
			'. $request->shiftid .',
			"'. $remarks .'",
			'.  $clear .',
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
