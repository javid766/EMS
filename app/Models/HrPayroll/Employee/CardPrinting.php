<?php

namespace App\Models\HrPayroll\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class CardPrinting extends Model
{
    use HasFactory;
    protected $table = 'att_employee';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/employee/card-printing';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getEmployees($request, $id, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				'companyid' => 'required | integer',
				'etypeid' => 'required | integer',

			]);

			if($validator->fails()) {

				return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
			}

			$userid = $request->userid;
			$companyid = $request->companyid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
		}
		
		$empData = CardPrinting::hydrate(
			DB::select('CALL sp_att_employee_card_printing_fill('. $userid .', '. $companyid .', '. $request->locationid .', '. $request->deptid .', '. $request->etypeid .')')
		);

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Card Printing", "ErrorMsg"=>"CALL sp_att_employee_card_printing_fill($userid,$companyid,$request->locationid, $request->deptid, $request->etypeid)");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'CardPrinting' => $empData,
				'status' => 'success'
			]);

		} else {

			return $empData;
		}

	}

	public function getEmployeesCardData($request, $id, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				'companyid' => 'required | integer',
				'empids'  => 'required | integer',
			]);

			if($validator->fails()) {

				return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
			}

			$userid = $request->userid;
			$companyid = $request->companyid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
		}
		
		$empCardData = CardPrinting::hydrate(
			DB::select('CALL sp_att_employee_card_printing_printemp('. $userid .', '. $companyid .', \''.$request->empids .'\')')
		);

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Card Printing", "ErrorMsg"=>"CALL sp_att_employee_card_printing_printemp($userid,$companyid, '$request->empids')");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'CardPrinting' => $empCardData,
				'status' => 'success'
			]);

		} else {

			return $empCardData;
		}

	}

}
