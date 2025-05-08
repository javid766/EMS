<?php

namespace App\Models\HrPayroll\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Utils;

class Location extends Model
{
    use HasFactory;

    protected $table = 'setup_location';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/setup/location';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getLocations($request, $id, $type) {

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

		$locations = Location::hydrate(
			DB::select('CALL sp_setup_location_get('. $id .', '. $userid .', '. $companyid .', '. $locationid .')')
		);

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Location", "ErrorMsg"=>"CALL sp_setup_location_get($id,$userid,$companyid,$locationid)");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'locations' => $locations,
				'status' => 'success'
			]);

		} else {

			return $locations;
		}

	}

	public function createLocation($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Location created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating location.', $type, $this->PAGE_LINK);
		}
	}

	public function updateLocation($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Location updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteLocation($id, $type) {

		if ($id > 0) {
			$action_type = $this->utilsModel->SP_ACTION_DELETE;
			$result = DB::select('CALL sp_setup_location_insertupdate(
				?, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			)[0];


			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Location", "ErrorMsg"=>"SET @id = $id; CALL sp_setup_location_insertupdate(@id, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '$action_type')");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */


			if ($result->status == $this->utilsModel->API_VALIDATION_ERROR) {
				return $this->utilsModel->returnResponseStatusMessage('error', $result->msg, $type, $this->PAGE_LINK);
			}

			return $this->utilsModel->returnResponseStatusMessage('success', 'Location deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Location id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'vcode'		  => 'required',
			'vname'		  => 'required',
			'companyid'   => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'address' 	  => 'required',
			'state' 	  => 'required',
			'zip' 		  => 'required',
			'countryid'   => 'required | integer',
			'telephone'   => 'required',
			'tollfree' 	  => 'required',
			//'isactive'	  => ' integer',
			'insertedby'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'	  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'	  => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
		]);
	}

	public function createUpdate($request, $id, $sp_type, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$companyid = $request->companyid;
			$insertedBy = $request->insertedby;
            $insertedIp = $request->insertedip;
            $updatedBy = $request->updatedby;
            $updatedIp = $request->updatedip;

		} else {

			$companyid = $request->session()->get('companyid', 0);
			$insertedBy = Auth::id();
			$insertedIp = $request->ip();
			$updatedBy = Auth::id();
			$updatedIp = $request->ip();
		}

		if($sp_type == 'u'){
			$set_id = "SET @id = $id;";
		}else{
			$set_id = "";
		}

		$vname = trim($request->vname);
		$isactive = (isset($request->isactive) ? $request->isactive : 0);

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Location", "ErrorMsg"=>"$set_id CALL sp_setup_location_insertupdate(@id, '$request->vcode', '$vname', $companyid, '$request->address', '$request->state', '$request->zip', '$request->countryid','$request->telephone', '$request->tollfree', $isactive, $insertedBy, '$insertedIp', $updatedBy, '$updatedIp', '$sp_type')");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */

		return DB::select('CALL sp_setup_location_insertupdate(
			?,
			"'. $request->vcode .'",
			"'. $vname .'",
			'. $companyid .',
			"'. $request->address .'",
			"'. $request->state .'",
			"'. $request->zip .'",
			'. $request->countryid .',
			"'. $request->telephone .'",
			"'. $request->tollfree .'",
			'. $isactive .',
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
