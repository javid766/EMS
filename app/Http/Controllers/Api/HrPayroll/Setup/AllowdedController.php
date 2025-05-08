<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Allowded;
use App\Models\Utils;

class AllowdedController extends Controller {
    
    public $attAllowdedModel;
	public $utilsModel;

	public function __construct() {

		$this->attAllowdedModel = new Allowded();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attAllowdedModel->getAllowdeds($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attAllowdedModel->createAllowded($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attAllowdedModel->updateAllowded($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attAllowdedModel->deleteAllowded($id, $this->utilsModel->CALL_TYPE_API);
    }
}
