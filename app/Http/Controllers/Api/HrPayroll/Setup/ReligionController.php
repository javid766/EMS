<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Religion;
use App\Models\Utils;

class ReligionController extends Controller {
    
    public $attReligionModel;
	public $utilsModel;

	public function __construct() {

		$this->attReligionModel = new Religion();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attReligionModel->getReligions($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attReligionModel->createReligion($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attReligionModel->updateReligion($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attReligionModel->deleteReligion($id, $this->utilsModel->CALL_TYPE_API);
    }
}
