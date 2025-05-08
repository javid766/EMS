<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Ramazan;
use App\Models\Utils;

class RamazanController extends Controller {
    
    public $attRamazanModel;
	public $utilsModel;

	public function __construct() {

		$this->attRamazanModel = new Ramazan();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attRamazanModel->getRamazans($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attRamazanModel->createRamazan($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attRamazanModel->updateRamazan($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attRamazanModel->deleteRamazan($id, $this->utilsModel->CALL_TYPE_API);
    }
}
