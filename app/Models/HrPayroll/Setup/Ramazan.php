<?php

namespace App\Models\HrPayroll\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class Ramazan extends Model
{
    use HasFactory;
    protected $table = 'att_setup_ramazan';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/setup/ramazan';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getRamazans($request, $id, $type) {

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

		$ramazans = Ramazan::hydrate(
			DB::select('CALL sp_att_setup_ramazan_get('. $id .', '. $userid .', '. $companyid .', '. $locationid .')')
		);

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'ramazans' => $ramazans,
				'status' => 'success'
			]);

		} else {

			return $ramazans;
		}

	}

	public function createRamazan($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Attendence Ramazan created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating attendence ramazan.', $type, $this->PAGE_LINK);
		}
	}

	public function updateRamazan($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Attendence Ramazan updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteRamazan($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_att_setup_ramazan_insertupdate(
				?, 0, 0, NOW(), NOW(), 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			return $this->utilsModel->returnResponseStatusMessage('success', 'Attendence Ramazan deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Attendence Ramazan id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'vcode'		 => 'required',
			'vname'		 => 'required',
			'datefrom' 	 => 'required',
			'dateto' 	 => 'required',
			//'companyid'  => 'required | integer',
			// 'isactive'		  => 'required | integer',
			'insertedby' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip' => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'	 => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'	 => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
		]);
	}

	public function createUpdate($request, $id, $sp_type, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$companyId = $request->companyid;
			$insertedBy = $request->insertedby;
            $insertedIp = $request->insertedip;
            $updatedBy = $request->updatedby;
            $updatedIp = $request->updatedip;

		} else {
			$companyId = $request->session()->get('companyid', 0);
			$insertedBy = Auth::id();
			$insertedIp = $request->ip();
			$updatedBy = Auth::id();
			$updatedIp = $request->ip();
		}

		return DB::select('CALL sp_att_setup_ramazan_insertupdate(
			?,
			"'. $request->vcode .'",
			"'. trim($request->vname) .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->datefrom)) .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->dateto)) .'",
			'. $companyId .',
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
