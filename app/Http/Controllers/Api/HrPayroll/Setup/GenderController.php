<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Gender;
use App\Models\Utils;

class GenderController extends Controller {
    
    public $attGenderModel;
	public $utilsModel;

	public function __construct() {

		$this->attGenderModel = new Gender();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attGenderModel->getGenders($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attGenderModel->createGender($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attGenderModel->updateGender($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attGenderModel->deleteGender($id, $this->utilsModel->CALL_TYPE_API);
    }
}
