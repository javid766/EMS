<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\EType;
use App\Models\Utils;

class ETypeController extends Controller {
    
    public $attETypeModel;
	public $utilsModel;

	public function __construct() {

		$this->attETypeModel = new EType();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attETypeModel->getETypes($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attETypeModel->createEType($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attETypeModel->updateEType($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attETypeModel->deleteEType($id, $this->utilsModel->CALL_TYPE_API);
    }
}
