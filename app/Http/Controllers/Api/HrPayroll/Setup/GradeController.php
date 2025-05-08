<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Grade;
use App\Models\Utils;

class GradeController extends Controller {
    
    public $attGradeModel;
	public $utilsModel;

	public function __construct() {

		$this->attGradeModel = new Grade();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->attGradeModel->getGrades($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->attGradeModel->createGrade($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->attGradeModel->updateGrade($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->attGradeModel->deleteGrade($id, $this->utilsModel->CALL_TYPE_API);
    }
}
