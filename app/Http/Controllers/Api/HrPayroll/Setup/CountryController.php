<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Country;
use App\Models\Utils;

class CountryController extends Controller {
    
    public $setupCountryModel;
	public $utilsModel;

	public function __construct() {

		$this->setupCountryModel = new Country();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->setupCountryModel->getCountries($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->setupCountryModel->createCountry($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->setupCountryModel->updateCountry($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->setupCountryModel->deleteCountry($id, $this->utilsModel->CALL_TYPE_API);
    }
}
