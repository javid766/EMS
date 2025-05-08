<?php

namespace App\Http\Controllers\Api\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Employee\CardPrinting;
use App\Models\Utils;

class CardPrintingController extends Controller {
    
    public $empFixTaxModel;
	public $utilsModel;

	public function __construct() {

		$this->empCardPrintingModel = new CardPrinting();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->empCardPrintingModel->getEmployees($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

}
