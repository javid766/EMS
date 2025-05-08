<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\SaleTypes;
use App\Models\Utils;

class SaleTypesController extends Controller {
    
    public $saleTypesModel;
	public $utilsModel;

	public function __construct() {

		$this->saleTypesModel = new SaleTypes();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->saleTypesModel->getSaleTypes($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->saleTypesModel->createSaleTypes($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->saleTypesModel->updateSaleTypes($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->saleTypesModel->deleteSaleTypes($id, $this->utilsModel->CALL_TYPE_API);
    }
}
