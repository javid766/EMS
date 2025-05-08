<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\LeftStatus;
use App\Models\Utils;

class LeftStatusController extends Controller {
    
    public $attLeftStatusModel;
	public $utilsModel;

	public function __construct() {

		$this->attLeftStatusModel = new LeftStatus();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attLeftStatusModel->getLeftStatuses($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attLeftStatusModel->createLeftStatus($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attLeftStatusModel->updateLeftStatus($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attLeftStatusModel->deleteLeftStatus($id, $this->utilsModel->CALL_TYPE_API);
    }
}
