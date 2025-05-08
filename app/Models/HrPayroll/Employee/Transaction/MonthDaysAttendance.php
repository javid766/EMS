<?php

namespace App\Models\HrPayroll\Employee\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class MonthDaysAttendance extends Model
{
    use HasFactory;

    protected $table = 'emp_month_days_attendence';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getEmpMonthAttDays($request, $id, $type) {

		$validator = Validator::make($request->all() ,[
			'employeeid' => 'required',
			'datein' => 'required',
			'dateout' => 'required',
			'userid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'companyid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
		]);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;
			$employeeid = $request->employeeid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
			$employeeid = $request->session()->get('employeeid', 0);
		}

		$datein = date('Y-m-d', strtotime($request->datein));
		$dateout = date('Y-m-d', strtotime($request->dateout));

		$empMonthAttDays = self::hydrate(DB::select('CALL sp_emp_month_days_attendence_get('. $employeeid .', "'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .', '. $locationid .')'));

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'emp_month_att_days' => $empMonthAttDays,
				'status' => 'success'
			]);

		} else {

			return $empMonthAttDays;
		}
	}

	public function createEmpMonthAttDay($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;
		
		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Month Att Days created successfully', 'id', $id, $type, $this->PAGE_LINK);
		
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an in error creating month att days.', $type, $this->PAGE_LINK);
		}
	}

	public function updateEmpMonthAttDay($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Month Att Days updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteEmpMonthAttDay($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_emp_month_days_attendence_insertupdate(
				?, 0, 0, NOW(), 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			return $this->utilsModel->returnResponseStatusMessage('success', 'Month Att Days deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Month Att Days id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'employeeid'		=> 'required | integer',
			'deptid'  			=> 'required',
			'att_month'			=> 'required',
			'attdays'   		=> 'required',
			'companyid' 		=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid'		=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedby'	  	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip'	  	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
		]);
	}

	public function createUpdate($request, $id, $sp_type, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {
			$userid = Auth::id();
			$insertedby = $request->insertedby;
            $insertedip = $request->insertedip;
            $companyid = $request->companyid;
            $locationid = $request->locationid;

		} else {
			$userid = Auth::id();
			$insertedby = Auth::id();
			$insertedip = $request->ip();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}
		return DB::select('CALL sp_emp_month_days_attendence_insertupdate(
			?,
			"'. $request->employeeid .'",
			"'. $request->deptid .'",
			"'. date('Y-m-d 00:00:00', strtotime($request->att_month)) .'",	
			"'. $request->attdays .'",
			'.  $companyid .',
			"'. $locationid .'",
			'.  $insertedby  .',
			"'. $insertedip .'",
			"'. $sp_type .'")',
			[
				$id
			]
		)[0]->id;
	}
}
