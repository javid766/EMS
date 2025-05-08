<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Bank;
use App\Models\Utils;

class BankController extends Controller {
    
    public $setupBankModel;
    public $utilsModel;

    public function __construct() {

        $this->setupBankModel = new Bank();
        $this->utilsModel = new Utils();
    }

    public function list(Request $request, $id = 0) {

        return $this->setupBankModel->getBanks($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

        return $this->setupBankModel->createBank($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

        return $this->setupBankModel->updateBank($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
        
        return $this->setupBankModel->deleteBank($id, $this->utilsModel->CALL_TYPE_API);
    }
}
