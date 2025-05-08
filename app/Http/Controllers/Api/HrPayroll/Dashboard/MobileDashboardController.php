<?php

namespace App\Http\Controllers\Api\HrPayroll\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\HrPayroll\Dashboard\MobileDashboard;
use App\Models\Utils;

class MobileDashboardController extends Controller {

	public $dashboardModel;
	public $utilsModel;

	public $FOR_MERGE;

	public function __construct() {

		$this->dashboardModel = new MobileDashboard();
		$this->utilsModel = new Utils();
		$this->FOR_MERGE = true;
	}

	public function graphData(Request $request) {

		$leaveBalance = $this->leaveBalance($request, $this->FOR_MERGE);
		$attendanceActivity = $this->attendanceActivity($request, $this->FOR_MERGE);
		$attendanceActivityEmp = $this->attendanceActivityEmp($request, $this->FOR_MERGE);

		$request['datein'] = date("Y-m-d", mktime(0, 0, 0, date("m"), 0));
		$request['dateout'] = date("Y-m-d", mktime(0, 0, 0, date("m")-1, 1));
		$previousMonthlyDetail = $this->attendanceActivityEmp($request, $this->FOR_MERGE);

		return response(array(
			'leave_balance'    => $leaveBalance, 
			'attendance_activity' => $attendanceActivity, 
			'attendance_activity_emp'   => array(
				'current'	   => $attendanceActivityEmp,
				'previous' 	   => $previousMonthlyDetail
			), 
			'status' => 'success'
		));
	}

	public function attendanceActivity(Request $request, $forMerge = false) {

		$data = $this->dashboardModel->getAttendanceActivity($request, $this->utilsModel->CALL_TYPE_API); //  returns model collection

		return $this->formatResultDataForGraph($data, $forMerge);
	}

	public function leaveBalance(Request $request, $forMerge = false) {

		$data = $this->dashboardModel->getLeaveBalance($request, $this->utilsModel->CALL_TYPE_API); //  returns model collection

		return $this->formatResultDataForGraph($data, $forMerge);
	}

	public function attendanceActivityEmp(Request $request, $forMerge = false) {

		$data = $this->dashboardModel->getAttendanceActivityEmp($request, $this->utilsModel->CALL_TYPE_API); //  returns model collection

		return $this->formatResultDataForGraph($data, $forMerge);
	}

	public function formatResultDataForGraph($data, $forMerge) {

		$status = $data['status']??'';

		if ($status != 'error' && count($data) > 0) {

			$labels = $data->shift();

			if ($forMerge) {

				return array(
					'labels' => $labels,
					'data' 	 => $data
				);

			} else {

				$formattedData = array(
					'labels' => $labels,
					'data' 	 => $data,
					'status' => 'success'
				);

				return response($formattedData);
			}
		}

		return $data;
	}
}
