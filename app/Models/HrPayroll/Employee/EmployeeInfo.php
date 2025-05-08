<?php

namespace App\Models\HrPayroll\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class EmployeeInfo extends Model
{
    use HasFactory;
    protected $table = 'att_employee';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = 'employee/employeeinfo';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getEmployees($request, $id, $type) {

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
		
		$empInfo = EmployeeInfo::hydrate(
			DB::select('CALL sp_att_employee_get('. $id .',  '. $request->get('isactive', 1) .', '. $userid .', '. $companyid .')')
		);



		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Employee Main", "ErrorMsg"=>"CALL sp_att_employee_get($id, ".$request->get('isactive', 1).", $userid,$companyid)");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


		// echo "<pre>";
  //       print_r($empInfo);
  //       exit;
        
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
		
		$empInfo = EmployeeInfo::hydrate(
			DB::select('CALL sp_att_employee_code_get('. $id .', '. $userid .', '. $companyid .', '. $locationid .')')
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

	public function createEmployees($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;
		
		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);
		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Employee created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an in error creating employee.', $type, $this->PAGE_LINK);
		}
	}

	public function updateEmployees($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Employee information updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteEmployees($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_att_employee_insertupdate(
				?, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			return $this->utilsModel->returnResponseStatusMessage('success', 'Employee deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Employee id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'empcode'			=> 'required',
			'machinecardno'		=> 'required',
			'locationid' 		=> 'required',
			'employeename'		=> 'required',
			'fathername' 		=> 'required',
			'deptid'  			=> 'required',
			'desgid'			=> 'required',
			'doj'   			=> 'required',
			'genderid' 			=> 'required',
			'shiftid' 			=> 'required',
			'cnicno' 			=> 'required',
			'email' 			=> 'required',
			'salary' 			=> 'required',
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

		if($sp_type == 'u'){
			$set_id = "SET @id = $id;";
		}else{
			$set_id = "";
		}


		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Employee Main", "ErrorMsg"=>"$set_id CALL sp_att_employee_insertupdate(@id,'$request->empcode', '$request->machinecardno', $request->etypeid, $request->locationid, '".trim($request->employeename)."', '".trim($request->fathername)."', $request->deptid, $request->desgid, '".date('Y-m-d 00:00:00', strtotime($request->doj))."', '$request->replaceof', $request->hiretypeid, $request->jobtypeid, '".date('Y-m-d 00:00:00', strtotime($request->dob))."', $request->genderid,$request->shiftid, '$request->contactno', '$request->email', '$request->emppic', '$request->cnicno', '".date('Y-m-d 00:00:00', strtotime($request->cnicexpiry))."', $request->bloodgroupid, $request->salary, $request->incometax, '".date('Y-m-d 00:00:00', strtotime($request->confirmationdate))."', ". (isset($request->issalarytobank) ? $request->issalarytobank : 0) .", $request->bankid,'". $request->advanceglcode ."', $request->offday1id, $request->offday2id, '". date('Y-m-d 00:00:00', strtotime($request->dol)) ."', $request->leftstatusid, '". $request->leftreason ."', $request->gradeid, $request->religionid, '". $request->education ."', '". $request->address ."', ". (isset($request->ishod) ? $request->ishod : 0) .", ". (isset($request->isregisterperson) ? $request->isregisterperson : 0) .", ". (isset($request->haveot) ? $request->haveot : 0) .", ". (isset($request->haveotoff) ? $request->haveotoff : 0) .", ". (isset($request->isstopsalary) ? $request->isstopsalary : 0) .", ". (isset($request->isexemptatt) ? $request->isexemptatt : 0) .", ". (isset($request->isexemptlatecoming) ? $request->isexemptlatecoming : 0) .", ". (isset($request->isshiftemployee) ? $request->isshiftemployee : 0) .", $userid, ". (isset($request->isactive) ? $request->isactive : 0) .", $companyid, $insertedby, '$insertedip', $updatedby, '$updatedip', '$sp_type')");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


		return DB::select('CALL sp_att_employee_insertupdate(
			?,
			"'. $request->empcode .'",
			"'. $request->machinecardno .'",
			"'. $request->etypeid .'",
			"'. $request->locationid .'",
			"'. trim($request->employeename) .'",
			"'. trim($request->fathername) .'",
			"'. $request->deptid .'",
			"'. $request->desgid .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->doj)) .'",	
			"'. $request->replaceof .'",
			"'. $request->hiretypeid .'",
			"'. $request->jobtypeid .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->dob)) .'",
			"'. $request->genderid .'",
			"'. $request->shiftid .'",
			"'. $request->contactno .'",
			"'. $request->email .'",
			"'. $request->emppic.'",
			"'. $request->cnicno .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->cnicexpiry)) .'",
			"'. $request->bloodgroupid .'",
			"'. $request->salary .'",
			"'. $request->incometax .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->confirmationdate)) .'",
			'. (isset($request->issalarytobank) ? $request->issalarytobank : 0) .',
			"'. $request->bankid .'",
			"'. $request->advanceglcode .'",
			"'. $request->offday1id .'",
			"'. $request->offday2id .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->dol)) .'",
			"'. $request->leftstatusid .'",
			"'. $request->leftreason .'",
			"'. $request->gradeid .'",
			"'. $request->religionid .'",
			"'. $request->education .'",
			"'. $request->address .'",
			'. (isset($request->ishod) ? $request->ishod : 0) .',
			'. (isset($request->isregisterperson) ? $request->isregisterperson : 0) .',
			'. (isset($request->haveot) ? $request->haveot : 0) .',
			'. (isset($request->haveotoff) ? $request->haveotoff : 0) .',
			'. (isset($request->isstopsalary) ? $request->isstopsalary : 0) .',
			'. (isset($request->isexemptatt) ? $request->isexemptatt : 0) .',
			'. (isset($request->isexemptlatecoming) ? $request->isexemptlatecoming : 0) .',
			'. (isset($request->isshiftemployee) ? $request->isshiftemployee : 0) .',
			'.  $userid .',
			'. (isset($request->isactive) ? $request->isactive : 0) .',
			'.  $companyid .',
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
