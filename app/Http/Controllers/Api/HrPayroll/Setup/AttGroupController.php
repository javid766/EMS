<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\AttGroup;
use App\Models\Utils;

class AttGroupController extends Controller {
    
    public $attAttGroupModel;
	public $utilsModel;

	public function __construct() {

		$this->attAttGroupModel = new AttGroup();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attAttGroupModel->getAttGroups($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attAttGroupModel->createAttGroup($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attAttGroupModel->updateAttGroup($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attAttGroupModel->deleteAttGroup($id, $this->utilsModel->CALL_TYPE_API);
    }
}
