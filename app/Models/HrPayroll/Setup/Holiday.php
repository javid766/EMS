<?php

namespace App\Models\HrPayroll\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class Holiday extends Model
{
    use HasFactory;
    protected $table = 'att_setup_holiday';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/setup/holiday';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getHolidays($request, $id, $type) {

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

		$holidays = Holiday::hydrate(
			DB::select('CALL sp_att_setup_holiday_get('. $id .', '. $userid .', '. $companyid .', '. $locationid .')')
		);

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Holiday", "ErrorMsg"=>"CALL sp_att_setup_holiday_get($id,$userid,$companyid,$locationid)");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'holidays' => $holidays,
				'status' => 'success'
			]);

		} else {

			return $holidays;
		}

	}

	public function createHoliday($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Holiday created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating holiday.', $type, $this->PAGE_LINK);
		}
	}

	public function updateHoliday($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Holiday updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteHoliday($id, $type) {

		if ($id > 0) {

			$action_type = $this->utilsModel->SP_ACTION_DELETE;
			$result = DB::select('CALL sp_att_setup_holiday_insertupdate(
				?, 0, 0, 0, NOW(), 0, 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Holiday", "ErrorMsg"=>"SET @id = $id; CALL sp_att_setup_holiday_insertupdate(@id, 0, 0, 0, NOW(), 0, 0, 0, 0, 0, 0, 0, '$action_type')");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */


			return $this->utilsModel->returnResponseStatusMessage('success', 'Holiday deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Holiday id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'vcode'		  => 'required',
			'vname'		  => 'required',
			'leavetypeid' => 'required | integer',
			'vdate' 	  => 'required',
			'companyid'   => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
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
		$vdate = date('Y-m-d 00:00:00', strtotime($request->vdate));

		if($sp_type == 'u'){
			$set_id = "SET @id = $id;";
		}else{
			$set_id = "";
		}


		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Holiday", "ErrorMsg"=>"$set_id CALL sp_att_setup_holiday_insertupdate(@id, '$request->vcode', '$vname', $request->leavetypeid, '$vdate', $companyid, $locationid, $is_active, $insertedBy, '$insertedIp', $updatedBy, '$updatedIp', '$sp_type')");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */

		return DB::select('CALL sp_att_setup_holiday_insertupdate(
			?,
			"'. $request->vcode .'",
			"'. $vname .'",
			'. $request->leavetypeid .',
			"'. $vdate .'",
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
