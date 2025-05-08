<?php

namespace App\Http\Controllers\Api\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Employee\LocalSale;
use App\Models\Utils;

class LocalSaleController extends Controller {
    
    public $localSaleModel;
	public $utilsModel;

	public function __construct() {

		$this->localSaleModel = new LocalSale();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->localSaleModel->getLocalSale($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->localSaleModel->createLocalSale($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->localSaleModel->updateLocalSale($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->localSaleModel->deleteLocalSale($id, $this->utilsModel->CALL_TYPE_API);
    }
}
