<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Tenant;
use App\Models\Utils;

class TenantController extends Controller {
    
    public $setupTenantModel;
    public $utilsModel;

    public function __construct() {

        $this->setupTenantModel = new Tenant();
        $this->utilsModel = new Utils();
    }

    public function list(Request $request, $id = 0) {

        return $this->setupTenantModel->getTenants($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

        return $this->setupTenantModel->createTenant($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

        return $this->setupTenantModel->updateTenant($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
        
        return $this->setupTenantModel->deleteTenant($id, $this->utilsModel->CALL_TYPE_API);
    }
}
