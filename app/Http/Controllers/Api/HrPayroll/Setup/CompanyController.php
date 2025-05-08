<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Company;
use App\Models\Utils;

class CompanyController extends Controller {
    
    public $setupCompanyModel;
    public $utilsModel;

    public function __construct() {

        $this->setupCompanyModel = new Company();
        $this->utilsModel = new Utils();
    }

    public function list(Request $request, $id = 0) {

        return $this->setupCompanyModel->getCompanies($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

        return $this->setupCompanyModel->createCompany($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

        return $this->setupCompanyModel->updateCompany($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
        
        return $this->setupCompanyModel->deleteCompany($id, $this->utilsModel->CALL_TYPE_API);
    }
}
