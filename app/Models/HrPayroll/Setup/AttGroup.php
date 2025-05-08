<?php

namespace App\Models\HrPayroll\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class AttGroup extends Model
{
    use HasFactory;
    protected $table = 'att_setup_attgroup';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getAttGroups($request, $id, $type) {

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

		$attgroups = AttGroup::hydrate(
			DB::select('CALL sp_att_setup_attgroup_get('. $id .', '. $userid .', '. $companyid .', '. $locationid .')')
		);

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'attgroups' => $attgroups,
				'status' => 'success'
			]);

		} else {

			return $attgroups;
		}

	}

	public function createAttGroup($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Attendence Att Group created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating attendence att group.', $type, $this->PAGE_LINK);
		}
	}

	public function updateAttGroup($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Attendence Att Group updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteAttGroup($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_att_setup_attgroup_insertupdate(
				?, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			return $this->utilsModel->returnResponseStatusMessage('success', 'Attendence Att Group deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Attendence Att Group id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'vcode'		 		=> 'required',
			'vname'		 		=> 'required',
			'vvalue' 	 		=> 'required',
			'isleave' 	 		=> 'required | integer',
			'issandwich' 		=> 'required | integer',
			'isoff' 			=> 'required | integer',
			'forleave'	 		=> 'required | integer',
			'parentid' 			=> 'required | integer',
			'pvalue' 			=> 'required',
			'useinleavebalance' => 'required | integer',
			// 'isactive'		  => 'required | integer',
			'insertedby' 		=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip' 		=> $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'	 		=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'	 		=> $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
		]);
	}

	public function createUpdate($request, $id, $sp_type, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$insertedBy = $request->insertedby;
            $insertedIp = $request->insertedip;
            $updatedBy = $request->updatedby;
            $updatedIp = $request->updatedip;

		} else {

			$insertedBy = Auth::id();
			$insertedIp = $request->ip();
			$updatedBy = Auth::id();
			$updatedIp = $request->ip();
		}

		return DB::select('CALL sp_att_setup_attgroup_insertupdate(
			?,
			"'. $request->vcode .'",
			"'. trim($request->vname) .'",
			"'. $request->vvalue .'",
			'. $request->isleave .',
			'. $request->issandwich .',
			'. $request->isoff .',
			'. $request->forleave .',
			'. $request->parentid .',
			"'. $request->pvalue .'",
			'. $request->useinleavebalance .',
			'. 1 .',
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
