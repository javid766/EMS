<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Location;
use App\Models\Utils;

class LocationController extends Controller {
    
    public $setupLocationModel;
	public $utilsModel;

	public function __construct() {

		$this->setupLocationModel = new Location();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->setupLocationModel->getLocations($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->setupLocationModel->createLocation($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->setupLocationModel->updateLocation($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->setupLocationModel->deleteLocation($id, $this->utilsModel->CALL_TYPE_API);
    }
}
