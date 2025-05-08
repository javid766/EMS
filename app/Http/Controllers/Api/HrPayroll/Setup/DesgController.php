<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Desg;
use App\Models\Utils;

class DesgController extends Controller {
    
    public $attDesgModel;
	public $utilsModel;

	public function __construct() {

		$this->attDesgModel = new Desg();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attDesgModel->getDesgs($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attDesgModel->createDesg($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attDesgModel->updateDesg($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attDesgModel->deleteDesg($id, $this->utilsModel->CALL_TYPE_API);
    }
}
