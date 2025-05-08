<?php

namespace App\Models\HrPayroll\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class DeptGroup extends Model
{
    use HasFactory;
    protected $table = 'att_setup_dept_group';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK;

	public function __construct() {

		$this->utilsModel = new Utils();
		$this->PAGE_LINK = route('setup-dept-group');
	}

	public function getDeptGroups($request, $id, $type) {

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

		$deptGroups = DeptGroup::hydrate(
			DB::select('CALL sp_att_setup_dept_group_get('. $id .', '. $userid .', '. $companyid .', '. $locationid .')')
		);

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Department Group", "ErrorMsg"=>"CALL sp_att_setup_dept_group_get($id,$userid,$companyid,$locationid)");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'dept_groups' => $deptGroups,
				'status' => 'success'
			]);

		} else {

			return $deptGroups;
		}

	}

	public function createDeptGroup($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Department Group created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating Department group.', $type, $this->PAGE_LINK);
		}
	}

	public function updateDeptGroup($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Department Group updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteDeptGroup($id, $type) {

		if ($id > 0) {

			$action_type = $this->utilsModel->SP_ACTION_DELETE;

			$result = DB::select('CALL sp_att_setup_dept_group_insertupdate(
				?, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			)[0];


			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Department Group", "ErrorMsg"=>"SET @id = $id; CALL sp_att_setup_dept_group_insertupdate(@id, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '$action_type')");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */


			if ($result->status == $this->utilsModel->API_VALIDATION_ERROR) {
				return $this->utilsModel->returnResponseStatusMessage('error', $result->msg, $type, $this->PAGE_LINK);
			}

			return $this->utilsModel->returnResponseStatusMessage('success', 'Department Group deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Department Group id is required", $type, $this->PAGE_LINK);
		}

	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'vcode'						=> 'required',
			'VName'						=> 'required',
			// 'SalariesPermanent' 		=> 'required',
			// 'SalariesContract'  		=> 'required',
			// 'AllowancePermanent'		=> 'required',
			// 'AllowanceContract' 		=> 'required',
			// 'AdvancePermanent'  		=> 'required',
			// 'AdvanceContract'   		=> 'required',
			// 'LoanPermanent'     		=> 'required',
			// 'LoanContract'				=> 'required',
			// 'OverTimePermanent' 		=> 'required',
			// 'OverTimeContract'  		=> 'required',
			// 'TaxPermanent'				=> 'required',
			// 'TaxContract'       		=> 'required',
			// 'OtherIncomePermanent'  	=> 'required',
			// 'OtherIncomeContract' 		=> 'required',
			// 'SalaryPayablePermanent' 	=> 'required',
			// 'SalaryPayableContract' 	=> 'required',
			// 'EmployeePFPermanent' 		=> 'required',
			// 'EmployeePFContract' 		=> 'required',
			// 'EmployerPFPermanent' 		=> 'required',
			// 'EmployerPFContract' 		=> 'required',
			// 'EmployeeEOBIPermanent' 	=> 'required',
			// 'EmployeeEOBIContract' 		=> 'required',
			// 'EmployerEOBIPermanent' 	=> 'required',
			// 'EmployerEOBIContract' 		=> 'required',
			'companyid' 				=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			// 'isactive'		  		=> 'required | integer',
			'insertedby'	  			=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip'	  			=> $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'		  			=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'		  			=> $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
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

		$VName = trim($request->VName);
		$isactive = (isset($request->isactive) ? $request->isactive : 0);


		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Department Group", "ErrorMsg"=>"$set_id CALL sp_att_setup_dept_group_insertupdate(@id, '$request->vcode', '$VName', '$request->SalariesPermanent', '$request->SalariesContract', '$request->AllowancePermanent', '$request->AllowanceContract', '$request->AdvancePermanent', '$request->AdvanceContract', '$request->LoanPermanent', '$request->LoanContract', '$request->OverTimePermanent', '$request->OverTimeContract', '$request->TaxPermanent', '$request->TaxContract' , '$request->OtherIncomePermanent', '$request->OtherIncomeContract', '$request->SalaryPayablePermanent', '$request->SalaryPayableContract', '$request->EmployeePFPermanent', '$request->EmployeePFContract', '$request->EmployerPFPermanent', '$request->EmployerPFContract', '$request->EmployeeEOBIPermanent', '$request->EmployeeEOBIContract', '$request->EmployerEOBIPermanent', '$request->EmployerEOBIContract', $companyid, $isactive, $insertedBy, '$insertedIp', $updatedBy, '$updatedIp', '$sp_type')");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


		return DB::select('CALL sp_att_setup_dept_group_insertupdate(
			?,
			"'. $request->vcode .'",
			"'. trim($request->VName) .'",
			"'. $request->SalariesPermanent .'",
			"'. $request->SalariesContract .'",
			"'. $request->AllowancePermanent .'",
			"'. $request->AllowanceContract .'",
			"'. $request->AdvancePermanent .'",
			"'. $request->AdvanceContract .'",
			"'. $request->LoanPermanent .'",
			"'. $request->LoanContract .'",
			"'. $request->OverTimePermanent .'",
			"'. $request->OverTimeContract .'",
			"'. $request->TaxPermanent .'",
			"'. $request->TaxContract .'",
			"'. $request->OtherIncomePermanent .'",
			"'. $request->OtherIncomeContract .'",
			"'. $request->SalaryPayablePermanent .'",
			"'. $request->SalaryPayableContract .'",
			"'. $request->EmployeePFPermanent .'",
			"'. $request->EmployeePFContract .'",
			"'. $request->EmployerPFPermanent .'",
			"'. $request->EmployerPFContract .'",
			"'. $request->EmployeeEOBIPermanent .'",
			"'. $request->EmployeeEOBIContract .'",
			"'. $request->EmployerEOBIPermanent .'",
			"'. $request->EmployerEOBIContract .'",
			'. $companyid .',
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
