<?php

namespace App\Models\HrPayroll\Dashboard;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class Dashboard extends Model
{
	use HasFactory;

	public $utilsModel;

	public $PAGE_LINK = 'dashboard';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getAttendanceActivityEmp($request, $type) {

		$validator = Validator::make($request->all() ,[
			'employeeid' => 'required',
			'datein' => 'required',
			'dateout' => 'required',
			'userid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'companyid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
		]);

		if($validator->fails()) {

			if ($type == $this->utilsModel->CALL_TYPE_API) {

				return array(
					'status' => 'error', 
					'message' => $validator->messages()->first()
				);
			}

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

		$attActivityEmp = self::hydrate(DB::select('CALL sp_dashboard_att_activity_emp('. $employeeid .', "'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .', '. $locationid .')'));

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'att_activity_emp' => $attActivityEmp,
				'status' => 'success'
			]);

		} else {

			return $attActivityEmp;
		}
	}

	public function getAttMain($request, $type) {

		$validator = Validator::make($request->all() ,[
			'datein' => 'required',
			'dateout' => 'required',
			'userid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'companyid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
		]);

		if($validator->fails()) {

			if ($type == $this->utilsModel->CALL_TYPE_API) {

				return array(
					'status' => 'error', 
					'message' => $validator->messages()->first()
				);
			}

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}

		$datein = date('Y-m-d', strtotime($request->datein));
		$dateout = date('Y-m-d', strtotime($request->dateout));

		$attMain = self::hydrate(DB::select('CALL sp_dashboard_att_main("'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .', '. $locationid .')'));

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'att_main' => $attMain,
				'status' => 'success'
			]);

		} else {

			return $attMain;
		}
	}

	public function getAttendanceActivity($request, $type) {

		$validator = Validator::make($request->all() ,[
			'employeeid' => 'required',
			'datein' => 'required',
			'dateout' => 'required',
			'userid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'companyid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
		]);

		if($validator->fails()) {

			if ($type == $this->utilsModel->CALL_TYPE_API) {

				return array(
					'status' => 'error', 
					'message' => $validator->messages()->first()
				);
			}

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

		$attActivity = self::hydrate(DB::select('CALL sp_dashboard_att_activity('. $employeeid .', "'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .', '. $locationid .')'));

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'att_activity' => $attActivity,
				'status' => 'success'
			]);

		} else {

			return $attActivity;
		}
	}

	public function getMonthWiseStrength($request, $type) {

		$validator = Validator::make($request->all() ,[
			'datein' => 'required',
			'dateout' => 'required',
			'userid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'companyid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
		]);

		if($validator->fails()) {

			if ($type == $this->utilsModel->CALL_TYPE_API) {

				return array(
					'status' => 'error', 
					'message' => $validator->messages()->first()
				);
			}

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}

		$datein = date('Y-m-d', strtotime($request->datein));
		$dateout = date('Y-m-d', strtotime($request->dateout));

		$monthWiseStrength = self::hydrate(DB::select('CALL sp_dashboard_att_dept_strength("'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .', '. $locationid .')'));

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'mw__strength' => $monthWiseStrength,
				'status' => 'success'
			]);

		} else {

			return $monthWiseStrength;
		}
	}

	public function getDeptWiseStrength($request, $type) {

		$validator = Validator::make($request->all() ,[
			'datein' => 'required',
			'dateout' => 'required',
			'userid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'companyid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
		]);

		if($validator->fails()) {

			if ($type == $this->utilsModel->CALL_TYPE_API) {

				return array(
					'status' => 'error', 
					'message' => $validator->messages()->first()
				);
			}

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}

		$datein = date('Y-m-d', strtotime($request->datein));
		$dateout = date('Y-m-d', strtotime($request->dateout));

		$deptWiseStrength = self::hydrate(DB::select('CALL sp_dashboard_att_strength_dept_wise_month("'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .', '. $locationid .')'));

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'dw_strength' => $deptWiseStrength,
				'status' => 'success'
			]);

		} else {

			return $deptWiseStrength;
		}
	}

	public function getDeptMonthWiseStrength($request, $type) {

		$validator = Validator::make($request->all() ,[
			'datein' => 'required',
			'dateout' => 'required',
			'userid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'companyid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
		]);

		if($validator->fails()) {

			if ($type == $this->utilsModel->CALL_TYPE_API) {

				return array(
					'status' => 'error', 
					'message' => $validator->messages()->first()
				);
			}

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}

		$datein = date('Y-m-d', strtotime($request->datein));
		$dateout = date('Y-m-d', strtotime($request->dateout));

		$deptMonthWiseStrength = self::hydrate(DB::select('CALL sp_dashboard_att_dept_strength_month_wise("'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .', '. $locationid .')'));

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'dept_mw_strength' => $deptMonthWiseStrength,
				'status' => 'success'
			]);

		} else {

			return $deptMonthWiseStrength;
		}
	}

	public function getEmpMonthWiseStrength($request, $type) {

		$validator = Validator::make($request->all() ,[
			'datein' => 'required',
			'dateout' => 'required',
			'userid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'companyid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
		]);

		if($validator->fails()) {

			if ($type == $this->utilsModel->CALL_TYPE_API) {

				return array(
					'status' => 'error', 
					'message' => $validator->messages()->first()
				);
			}

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}

		$datein = date('Y-m-d', strtotime($request->datein));
		$dateout = date('Y-m-d', strtotime($request->dateout));

		$empMonthWiseStrength = self::hydrate(DB::select('CALL sp_dashboard_att_emp_strength_month_wise("'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .', '. $locationid .')'));

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'emp_mw_strength' => $empMonthWiseStrength,
				'status' => 'success'
			]);

		} else {

			return $empMonthWiseStrength;
		}
	}

	public function getDeptWiseSalary($request, $type) {

		$validator = Validator::make($request->all() ,[
			'datein' => 'required',
			'dateout' => 'required',
			'userid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'companyid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
		]);

		if($validator->fails()) {

			if ($type == $this->utilsModel->CALL_TYPE_API) {

				return array(
					'status' => 'error', 
					'message' => $validator->messages()->first()
				);
			}

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}

		$datein = date('Y-m-d', strtotime($request->datein));
		$dateout = date('Y-m-d', strtotime($request->dateout));

		$deptWiseSalary = self::hydrate(DB::select('CALL sp_dashboard_att_salary_dept_wise("'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .', '. $locationid .')'));

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'dw_salary' => $deptWiseSalary,
				'status' => 'success'
			]);

		} else {

			return $deptWiseSalary;
		}
	}

	public function getMonthWiseSalary($request, $type) {

		$validator = Validator::make($request->all() ,[
			'datein' => 'required',
			'dateout' => 'required',
			'userid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'companyid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
		]);

		if($validator->fails()) {

			if ($type == $this->utilsModel->CALL_TYPE_API) {

				return array(
					'status' => 'error', 
					'message' => $validator->messages()->first()
				);
			}

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}

		$datein = date('Y-m-d', strtotime($request->datein));
		$dateout = date('Y-m-d', strtotime($request->dateout));

		$monthWiseSalary = self::hydrate(DB::select('CALL sp_dashboard_att_salary_month_wise("'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .', '. $locationid .')'));

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'mw_salary' => $monthWiseSalary,
				'status' => 'success'
			]);

		} else {

			return $monthWiseSalary;
		}
	}

	public function validateParams($request, $type) {

		$validator = Validator::make($request->all() ,[
			'datein' => 'required',
			'dateout' => 'required',
			'employeeid' => isset($request->employeeid) ? 'required | integer' : '',
			'userid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'companyid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
		]);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		return "true";
	}
}
