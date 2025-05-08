<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\EntitlementType;
use App\Models\Utils;

class EntitlementTypeController extends Controller {
    
    public $attEntitlementTypeModel;
	public $utilsModel;

	public function __construct() {

		$this->attEntitlementTypeModel = new EntitlementType();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attEntitlementTypeModel->getEntitlementTypes($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attEntitlementTypeModel->createEntitlementType($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attEntitlementTypeModel->updateEntitlementType($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attEntitlementTypeModel->deleteEntitlementType($id, $this->utilsModel->CALL_TYPE_API);
    }
}
