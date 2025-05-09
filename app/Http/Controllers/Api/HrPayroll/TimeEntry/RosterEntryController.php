<?php

namespace App\Http\Controllers\Api\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\TimeEntry\RosterEntry;
use App\Models\Utils;

class RosterEntryController extends Controller {
    
    public $rosterEntryModel;
    public $utilsModel;

    public function __construct() {
        $this->rosterEntryModel = new RosterEntry();
        $this->utilsModel = new Utils();
    }

    public function list(Request $request, $id = 0) {
        return $this->rosterEntryModel->getRosterEntries($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {
        return $this->rosterEntryModel->createRosterEntry($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {
        return $this->rosterEntryModel->updateRosterEntry($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {       
        return $this->rosterEntryModel->deleteRosterEntry($id, $this->utilsModel->CALL_TYPE_API);
    }
}
