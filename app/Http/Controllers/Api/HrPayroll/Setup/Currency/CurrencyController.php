<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup\Currency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Currency;
use App\Models\Utils;

class CurrencyController extends Controller {
    
    public $setupCurrencyModel;
	public $utilsModel;

	public function __construct() {

		$this->setupCurrencyModel = new Currency();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->setupCurrencyModel->getCurrencies($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->setupCurrencyModel->createCurrency($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->setupCurrencyModel->updateCurrency($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->setupCurrencyModel->deleteCurrency($id, $this->utilsModel->CALL_TYPE_API);
    }
}
