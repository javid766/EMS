<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Dept;
use App\Models\Utils;

class DeptController extends Controller {
    
    public $attDeptModel;
	public $utilsModel;

	public function __construct() {

		$this->attDeptModel = new Dept();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attDeptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attDeptModel->createDept($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attDeptModel->updateDept($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attDeptModel->deleteDept($id, $this->utilsModel->CALL_TYPE_API);
    }
}
