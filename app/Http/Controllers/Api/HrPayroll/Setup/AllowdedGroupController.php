<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\AllowdedGroup;
use App\Models\Utils;

class AllowdedGroupController extends Controller {
    
    public $attAllowdedGroupModel;
	public $utilsModel;

	public function __construct() {

		$this->attAllowdedGroupModel = new AllowdedGroup();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attAllowdedGroupModel->getAllowdedGroups($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attAllowdedGroupModel->createAllowdedGroup($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attAllowdedGroupModel->updateAllowdedGroup($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attAllowdedGroupModel->deleteAllowdedGroup($id, $this->utilsModel->CALL_TYPE_API);
    }
}
