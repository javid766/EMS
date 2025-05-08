<?php

namespace App\Http\Controllers\Api\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\TimeEntry\LeaveApply;
use App\Models\Utils;

class LeaveApplyController extends Controller {
    
    public $leaveApplyModel;
    public $utilsModel;

    public function __construct() {

        $this->leaveApplyModel = new LeaveApply();
        $this->utilsModel = new Utils();
    }

    public function list(Request $request, $id = 0) {

        return $this->leaveApplyModel->getLeaveEntries($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

        return $this->leaveApplyModel->createLeaveEntry($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

        return $this->leaveApplyModel->updateLeaveEntry($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
        
        return $this->leaveApplyModel->deleteLeaveEntry($id, $this->utilsModel->CALL_TYPE_API);
    }
}
