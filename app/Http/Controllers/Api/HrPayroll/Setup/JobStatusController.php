<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\JobStatus;
use App\Models\Utils;

class JobStatusController extends Controller {
    
    public $attJobStatusModel;
	public $utilsModel;

	public function __construct() {

		$this->attJobStatusModel = new JobStatus();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attJobStatusModel->getJobStatuses($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attJobStatusModel->createJobStatus($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attJobStatusModel->updateJobStatus($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attJobStatusModel->deleteJobStatus($id, $this->utilsModel->CALL_TYPE_API);
    }
}
