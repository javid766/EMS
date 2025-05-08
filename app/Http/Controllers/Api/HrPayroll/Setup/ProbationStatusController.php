<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\ProbationStatus;
use App\Models\Utils;

class ProbationStatusController extends Controller {
    
    public $attProbationStatusModel;
	public $utilsModel;

	public function __construct() {

		$this->attProbationStatusModel = new ProbationStatus();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attProbationStatusModel->getProbationStatuses($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attProbationStatusModel->createProbationStatus($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attProbationStatusModel->updateProbationStatus($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attProbationStatusModel->deleteProbationStatus($id, $this->utilsModel->CALL_TYPE_API);
    }
}
