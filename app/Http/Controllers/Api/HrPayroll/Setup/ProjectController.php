<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Project;
use App\Models\Utils;

class ProjectController extends Controller {
    
    public $setupProjectModel;
	public $utilsModel;

	public function __construct() {

		$this->setupProjectModel = new Project();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->setupProjectModel->getProjects($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->setupProjectModel->createProject($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->setupProjectModel->updateProject($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->setupProjectModel->deleteProject($id, $this->utilsModel->CALL_TYPE_API);
    }
}
