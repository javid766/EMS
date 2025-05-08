<?php

namespace App\Http\Controllers\Api\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

use App\Models\HrPayroll\Employee\SalLoanDeduct;
use App\Models\Utils;

class SalLoanDeductController extends Controller {
    
    public $salLoanDeductModel;
	public $utilsModel;

	public function __construct() {

		$this->salLoanDeductModel = new SalLoanDeduct();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->salLoanDeductModel->getSalLoanDeducts($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

        if (!isset($request->data))
            return $this->utilsModel->returnResponseStatusMessage('error', "Data is required", $this->utilsModel->CALL_TYPE_API, '');

        $data = json_decode($request->data);

        foreach($data as $row) {

            $id = $row->id;
            $amount = $row->amount;
            
            if ($id == 0) {

                if ($amount > 0 && $row->finalized > 0) {

                    $createRequest = clone $request;
                    $createRequest['loanid'] = $row->loanid;
                    $createRequest['employeeid'] = $row->employeeid;
                    $createRequest['amount'] = $row->amount;
                    $createRequest['datein'] = $row->datein;
                    $createRequest['dateout'] = $row->dateout;
                    $createRequest['companyid'] = $row->companyid;
                    $createRequest['locationid'] = $row->locationid;
                    $createRequest['insertedby'] = $row->insertedby;
                    $createRequest['insertedip'] = $row->insertedip;
                    $createRequest['updatedby'] = $row->updatedby;
                    $createRequest['updatedip'] = $row->updatedip;

                    $this->salLoanDeductModel->createSalLoanDeduct($createRequest, $this->utilsModel->CALL_TYPE_API);
                }

            } else {

                if ($amount = 0 && $row->finalized == 0) {

                    $this->salLoanDeductModel->deleteSalLoanDeduct($id, $this->utilsModel->CALL_TYPE_API);
                
                } else {

                    $updateRequest = clone $request;
                    $updateRequest['loanid'] = $row->loanid;
                    $updateRequest['employeeid'] = $row->employeeid;
                    $updateRequest['amount'] = $row->amount;
                    $updateRequest['datein'] = $row->datein;
                    $updateRequest['dateout'] = $row->dateout;
                    $updateRequest['companyid'] = $row->companyid;
                    $updateRequest['locationid'] = $row->locationid;
                    $updateRequest['insertedby'] = $row->insertedby;
                    $updateRequest['insertedip'] = $row->insertedip;
                    $updateRequest['updatedby'] = $row->updatedby;
                    $updateRequest['updatedip'] = $row->updatedip;
                    
                    $this->salLoanDeductModel->updateSalLoanDeduct($updateRequest, $id, $this->utilsModel->CALL_TYPE_API);
                }
            }
        }

        return $this->utilsModel->returnResponseStatusMessage('success', "Data saved successfully", $this->utilsModel->CALL_TYPE_API, '');
    }

    public function update(Request $request, $id) {


    	return $this->salLoanDeductModel->updateSalLoanDeduct($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->salLoanDeductModel->deleteSalLoanDeduct($id, $this->utilsModel->CALL_TYPE_API);
    }
}
