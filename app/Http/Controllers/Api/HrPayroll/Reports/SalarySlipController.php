<?php

namespace App\Http\Controllers\Api\HrPayroll\Reports;

use \PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Reports\SalarySlip;
use App\Models\Utils;

class SalarySlipController extends Controller {

	public $salarySlipModel;
	public $utilsModel;

	public function __construct() {

		$this->salarySlipModel = new SalarySlip();
		$this->utilsModel = new Utils();
	}

	public function list(Request $request, $employeeid) {

		$fields = array(
			'datein', 'dateout', 'userid', 'companyid', 'locationid'
		);

		$message = ($employeeid == 0 ? 'employeeid is required' : '');

		foreach ($fields as $field) {

			if ($request->has($field)) {

				$request[$field] = $request->input($field);

			} else {

				$message .= (($message == '' ? '' : ', ') . $field . ' is required');
			}
		}

		if ($message != '') {

			return response([
				'status' => 'error',
				'message' => $message
			]);
		}

		$data = $this->salarySlipModel->getSalarySlip($request, $employeeid, $this->utilsModel->CALL_TYPE_API_PDF);

		if (count($data) > 0) {
			
			$data = $data[0];
		}

		$pdf = PDF::loadView('attendance.reports.document_view.salary-slip', $data);

		return $pdf->download();

		$fileName = 'salaryslip_test.pdf';
		$fileUrl = 'files/documentview/'.$fileName;

		$pdf->save(base_path('public/'.$fileUrl));

		return response([
			'status' => 'success',
			'url' => asset('files/documentview/salaryslip.pdf')
		]);
	}

	public function salarySlip(Request $request, $employeeid = 0) {

		$fields = array(
			'datein', 'dateout', 'userid', 'companyid', 'locationid'
		);

		$message = ($employeeid == 0 ? 'employeeid is required' : '');

		foreach ($fields as $field) {

			if ($request->has($field)) {

				$request[$field] = $request->input($field);

			} else {

				$message .= (($message == '' ? '' : ', ') . $field . ' is required');
			}
		}

		if ($message != '') {

			return response([
				'status' => 'error',
				'message' => $message
			]);
		}

		$data = $this->salarySlipModel->getSalarySlip($request, $employeeid, $this->utilsModel->CALL_TYPE_API_PDF);

		if (count($data) > 0) {
			
			$data = $data[0];
		}

		$pdf = PDF::loadView('attendance.reports.document_view.salary-slip', $data);

		$fileName = 'salary-slip.pdf';
		$fileUrl = 'files/documentview/'.$fileName;

		$pdf->save(base_path('public/'.$fileUrl));

		return response([
			'status' => 'success',
			'url' => asset('files/documentview/salary-slip.pdf')
		]);
	}
}
