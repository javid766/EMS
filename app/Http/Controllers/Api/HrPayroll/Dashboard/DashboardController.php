<?php

namespace App\Http\Controllers\Api\HrPayroll\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\HrPayroll\Dashboard\Dashboard;
use App\Models\Utils;

class DashboardController extends Controller {

	public $dashboardModel;
	public $utilsModel;

	public $FOR_MERGE;

	public function __construct() {

		$this->dashboardModel = new Dashboard();
		$this->utilsModel = new Utils();
		$this->FOR_MERGE = true;
	}

	public function attendanceActivityEmp(Request $request, $forMerge = false) {

		return $this->dashboardModel->getAttendanceActivityEmp($request, $this->utilsModel->CALL_TYPE_API);
	}

	public function attMain(Request $request, $forMerge = false) {

		return $this->dashboardModel->getAttMain($request, $this->utilsModel->CALL_TYPE_API);
	}

	public function attendanceActivity(Request $request, $forMerge = false) {

		return $this->dashboardModel->getAttendanceActivity($request, $this->utilsModel->CALL_TYPE_API);
	}

	public function monthWiseStrength(Request $request, $forMerge = false) {

		return $this->dashboardModel->getMonthWiseStrength($request, $this->utilsModel->CALL_TYPE_API);
	}

	public function deptWiseStrength(Request $request, $forMerge = false) {

		return $this->dashboardModel->getDeptWiseStrength($request, $this->utilsModel->CALL_TYPE_API);
	}

	public function deptMonthWiseStrength(Request $request, $forMerge = false) {

		return $this->dashboardModel->getDeptMonthWiseStrength($request, $this->utilsModel->CALL_TYPE_API);
	}

	public function empMonthWiseStrength(Request $request, $forMerge = false) {

		return $this->dashboardModel->getEmpMonthWiseStrength($request, $this->utilsModel->CALL_TYPE_API);
	}

	public function deptWiseSalary(Request $request, $forMerge = false) {

		return $this->dashboardModel->getDeptWiseSalary($request, $this->utilsModel->CALL_TYPE_API);
	}

	public function monthWiseSalary(Request $request, $forMerge = false) {

		return $this->dashboardModel->getMonthWiseSalary($request, $this->utilsModel->CALL_TYPE_API);
	}
}
