<?php

namespace App\Http\Controllers\Api\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\TimeEntry\OfficialVisitEntry;
use App\Models\Utils;

class OfficialVisitEntryController extends Controller {
    
    public $officialVisitEntryModel;
	public $utilsModel;

	public function __construct() {

		$this->officialVisitEntryModel = new OfficialVisitEntry();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->officialVisitEntryModel->getOfficialVisitEntries($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->officialVisitEntryModel->createOfficialVisitEntry($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->officialVisitEntryModel->updateOfficialVisitEntry($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->officialVisitEntryModel->deleteOfficialVisitEntry($id, $this->utilsModel->CALL_TYPE_API);
    }
}
