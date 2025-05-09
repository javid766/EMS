<?php

namespace App\Models\HrPayroll\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class Shift extends Model
{
    use HasFactory;
    protected $table = 'att_setup_shift';
	public $timestamps = false;

	public $utilsModel;

	public $locationid;

	public $PAGE_LINK = '/setup/shift';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getShifts($request, $id, $type) {

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

		$shifts = Shift::hydrate(
			DB::select('CALL sp_att_setup_shift_get('. $id .', '. $userid .', '. $companyid .', '. $locationid .')')
		);

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'shifts' => $shifts,
				'status' => 'success'
			]);

		} else {

			return $shifts;
		}

	}

	public function createShift($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Shift created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating shift.', $type, $this->PAGE_LINK);
		}
	}

	public function updateShift($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Shift updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteShift($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_att_setup_shift_insertupdate(
				?, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			)[0];

			if ($result->status == $this->utilsModel->API_VALIDATION_ERROR) {
				return $this->utilsModel->returnResponseStatusMessage('error', $result->msg, $type, $this->PAGE_LINK);
			}

			return $this->utilsModel->returnResponseStatusMessage('success', 'Shift deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Shift id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'vcode'		 	=> 'required',
			'vname'		 	=> 'required',
			'timein' 	 	=> 'required',
			'timeout' 	 	=> 'required',
			'resttimefrom' 	=> 'required',
			'resttimeto' 	=> 'required',
			'workinghrs' 	=> 'required',
			'relaxtime' 	=> 'required',
			'minatttime' 	=> 'required',
			'minhdtime' 	=> 'required',
			// 'isroster'  	=> 'required | integer',
			// 'issecurity'  	=> 'required | integer',
			'companyid'  	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			//'locationid'  	=> 'required | integer',
			// 'isactive'		  => 'required | integer',
			'insertedby' 	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip' 	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'	 	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'	 	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
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

		return DB::select('CALL sp_att_setup_shift_insertupdate(
			?,
			"'. $request->vcode .'",
			"'. trim($request->vname) .'",
			"'. $request->timein .'",
			"'. $request->timeout .'",
			"'. $request->resttimefrom .'",
			"'. $request->resttimeto .'",
			"'. $request->workinghrs .'",
			"'. $request->relaxtime .'",
			"'. $request->minatttime .'",
			"'. $request->minhdtime .'",
			'. (isset($request->isroster) ? $request->isroster : 0) .',
			'. (isset($request->issecurity) ? $request->issecurity : 0) .',
			'. $companyid .',
			'. $request->session()->get('locationid', 0) .',
			'. (isset($request->isactive) ? $request->isactive : 0) .',
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
