<?php

namespace App\Http\Controllers\Api\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\TimeEntry\AttEntry;
use App\Models\Utils;

class AttEntryController extends Controller {
    
    public $AttEntryModel;
    public $utilsModel;

    public function __construct() {

        $this->AttEntryModel = new AttEntry();
        $this->utilsModel = new Utils();
    }

    public function list(Request $request, $id = 0) {

        return $this->AttEntryModel->getAttEntries($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function getEmployees(Request $request, $id = 0) {

        return $this->AttEntryModel->getAttEmployeesEntries($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

        return $this->AttEntryModel->createAttEntry($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

        return $this->AttEntryModel->updateAttEntry($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
        
        return $this->AttEntryModel->deleteAttEntry($id, $this->utilsModel->CALL_TYPE_API);
    }
}
