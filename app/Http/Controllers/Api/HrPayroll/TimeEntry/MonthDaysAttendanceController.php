<?php

namespace App\Http\Controllers\Api\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\TimeEntry\MonthDaysAttendance;
use App\Models\Utils;

class MonthDaysAttendanceController extends Controller {
    
    public $monthDaysAttModel;
    public $utilsModel;

    public function __construct() {
        $this->monthDaysAttModel = new MonthDaysAttendance();
        $this->utilsModel = new Utils();
    }

    public function list(Request $request, $id = 0) {
        return $this->monthDaysAttModel->getMonthDaysAttandance($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {
        return $this->monthDaysAttModel->createMonthDaysAttandance($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {
        return $this->monthDaysAttModel->updateMonthDaysAttandance($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {       
        return $this->monthDaysAttModel->deleteMonthDaysAttandance($id, $this->utilsModel->CALL_TYPE_API);
    }
}
