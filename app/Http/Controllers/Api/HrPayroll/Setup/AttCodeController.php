<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\AttCode;
use App\Models\Utils;

class AttCodeController extends Controller {
    
    public $attAttCodeModel;
	public $utilsModel;

	public function __construct() {

		$this->attAttCodeModel = new AttCode();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attAttCodeModel->getAttCodes($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attAttCodeModel->createAttCode($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attAttCodeModel->updateAttCode($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attAttCodeModel->deleteAttCode($id, $this->utilsModel->CALL_TYPE_API);
    }
}
