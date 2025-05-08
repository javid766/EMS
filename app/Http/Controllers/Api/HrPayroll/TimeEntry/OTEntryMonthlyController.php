<?php

namespace App\Http\Controllers\Api\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\TimeEntry\OTEntryMonthly;
use App\Models\Utils;

class OTEntryMonthlyController extends Controller {
    
    public $otMonthlyModel;
    public $utilsModel;

    public function __construct() {
        $this->otMonthlyModel = new OTEntryMonthly();
        $this->utilsModel = new Utils();
    }

    public function list(Request $request, $id = 0) {
        return $this->otMonthlyModel->getOTEntriesMonthly($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {
        return $this->otMonthlyModel->createOTEntryMonthly($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {
        return $this->otMonthlyModel->updateOTEntryMonthly($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {       
        return $this->otMonthlyModel->deleteOTEntryMonthly($id, $this->utilsModel->CALL_TYPE_API);
    }
}
