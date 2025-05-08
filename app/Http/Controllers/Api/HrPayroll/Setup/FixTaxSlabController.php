<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\FixTaxSlab;
use App\Models\Utils;

class FixTaxSlabController extends Controller {
    
    public $fixTaxSlabModel;
	public $utilsModel;

	public function __construct() {

		$this->fixTaxSlabModel = new FixTaxSlab();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->fixTaxSlabModel->getFixTaxSlabs($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->fixTaxSlabModel->createFixTaxSlab($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->fixTaxSlabModel->updateFixTaxSlab($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->fixTaxSlabModel->deleteFixTaxSlab($id, $this->utilsModel->CALL_TYPE_API);
    }
}
