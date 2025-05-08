<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\DeptGroup;
use App\Models\Utils;

class DeptGroupController extends Controller {
    
    public $attDeptGroupModel;
	public $utilsModel;

	public function __construct() {

		$this->attDeptGroupModel = new DeptGroup();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attDeptGroupModel->getDeptGroups($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attDeptGroupModel->createDeptGroup($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attDeptGroupModel->updateDeptGroup($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attDeptGroupModel->deleteDeptGroup($id, $this->utilsModel->CALL_TYPE_API);
    }
}
