<?php

namespace App\Models\HrPayroll\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class TrialEmployee extends Model
{
    use HasFactory;
    protected $table = 'att_trial_employee';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = 'employee/trial-employee-entry';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getTrialEmployees($request, $id, $type) {

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

		
		$empInfo = TrialEmployee::hydrate(
			DB::select('CALL sp_att_trial_employee_get('. $id .',  '. $request->isactive .', '. $userid .', '. $companyid .')')
		);

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'emp_info' => $empInfo,
				'status' => 'success'
			]);

		} else {

			return $empInfo;
		}

	}

	public function getEmployeeCode($request, $id, $type) {

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
		
		$empInfo = TrialEmployee::hydrate(
			DB::select('CALL sp_att_trial_employee_code_get('. $id .', '. $userid .', '. $companyid .', '. $locationid .')')
		);

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'emp_info' => $empInfo,
				'status' => 'success'
			]);

		} else {

			return $empInfo;
		}

	}
	public function createTrialEmployees($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;
		
		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);
		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Trial Employee created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an in error creating Trial employee.', $type, $this->PAGE_LINK);
		}
	}

	public function updateTrialEmployees($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Trial Employee information updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteTrialEmployees($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_att_trial_employee_insertupdate(
				?,0,0,0,0,0,NOW(),NOW(),0,0,NOW(),0,0,0,0,0,0,1,1,0,0,0,0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			return $this->utilsModel->returnResponseStatusMessage('success', 'Trial Employee deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Trial Employee id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'empcode'			=> 'required',
			'employeename'		=> 'required',
			'fathername' 		=> 'required',
			'deptid'  			=> 'required',
			'shiftid' 			=> 'required',
			'doj'   			=> 'required',
			'cnicno' 			=> 'required',
			'companyid' 		=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			// 'isactive'		=> 'required | integer',
			'insertedby'	  	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip'	  	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'		  	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'		  	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
		]);
	}

	public function createUpdate($request, $id, $sp_type, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {
			$userid = Auth::id();
			$insertedby = $request->insertedby;
            $insertedip = $request->insertedip;
            $updatedby = $request->updatedby;
            $updatedip = $request->updatedip;
            $companyid = $request->companyid;

		} else {
			$userid = Auth::id();
			$insertedby = Auth::id();
			$insertedip = $request->ip();
			$updatedby = Auth::id();
			$updatedip = $request->ip();
			$companyid = $request->session()->get('companyid', 0);
		}
		
		return DB::select('CALL sp_att_trial_employee_insertupdate(
			?,
			"'. $request->empcode .'",
			"'. trim($request->employeename) .'",
			"'. trim($request->fathername) .'",
			'. $request->deptid .',
			'. $request->shiftid .',
			"'. date('Y-m-d 00:00:00', strtotime($request->doj)) .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->dob)) .'",	
			"'. $request->contactno .'",
			"'. $request->cnicno .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->closingdate)) .'",
			"'. $request->closingstatus .'",
			'. $request->hiretypeid .',
			"'. $request->refrenceno .'",
			"'. $request->emppic.'",			
			"'. $request->address .'",
			'. (isset($request->isactive) ? $request->isactive : 0) .',
			'.  $companyid .',
			'.  $userid .',
			'.  $insertedby  .',
			"'. $insertedip .'",
			'.  $updatedby .',
			"'. $updatedip .'",
			"'. $sp_type .'")',
			[
				$id
			]
		)[0]->id;
	}
}
