<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup\Currency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Currency\CurrencyExchange;
use App\Models\Utils;

class CurrencyExchangeController extends Controller {
    
    public $setupCurrencyExchangeModel;
	public $utilsModel;

	public function __construct() {

		$this->setupCurrencyExchangeModel = new CurrencyExchange();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->setupCurrencyExchangeModel->getCurrencyExchanges($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->setupCurrencyExchangeModel->createCurrencyExchange($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->setupCurrencyExchangeModel->updateCurrencyExchange($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->setupCurrencyExchangeModel->deleteCurrencyExchange($id, $this->utilsModel->CALL_TYPE_API);
    }
}
