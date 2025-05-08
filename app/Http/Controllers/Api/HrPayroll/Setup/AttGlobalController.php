<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\AttGlobal;
use App\Models\Utils;

class AttGlobalController extends Controller
{
    public $attAttGlobalModel;
	public $utilsModel;

	public function __construct() {

		$this->attAttGlobalModel = new AttGlobal();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attAttGlobalModel->getAttGlobals($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attAttGlobalModel->createAttGlobal($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attAttGlobalModel->updateAttGlobal($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attAttGlobalModel->deleteAttGlobal($id, $this->utilsModel->CALL_TYPE_API);
    }
}
