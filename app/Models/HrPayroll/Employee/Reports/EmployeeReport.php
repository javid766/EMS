<?php

namespace App\Models\HrPayroll\Employee\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class EmployeeReport extends Model
{
    use HasFactory;
    protected $table = 'att_employee';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/employee/report';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getEmpData($request, $id, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				'companyid' => 'required | integer',
				'locationid' => 'required | integer',
			]);

			if($validator->fails()) {

				return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
			}

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
			
		}
		$attEmployeeData = '';	
		if ($request->attfilter == 'empdetailreport') {
			$attEmployeeData = DB::select('CALL sp_rpt_att_employee_detail('. $userid .','. $companyid .','. $locationid .', \''.$request->cwhere .' \')');			
		}
		if ($request->attfilter == 'deptwisestrength') {
			$attEmployeeData = DB::select('CALL sp_rpt_att_employee_strength_deptwise('. $userid .','. $companyid .','. $locationid .', \''.$request->cwhere .' \')');
		}
		if ($request->attfilter == 'desgwisestrength') {
			$attEmployeeData = DB::select('CALL sp_rpt_att_employee_strength_desgwise('. $userid .','. $companyid .','. $locationid .', \''.$request->cwhere .' \')');
		}
		if ($request->attfilter == 'empcardreport') {
			$attEmployeeData = DB::select('CALL sp_rpt_att_employee_detail('. $userid .','. $companyid .','. $locationid .', \''.$request->cwhere .' \')');
		}
		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'EmployeeReport' => $attEmployeeData,
				'status' => 'success'
			]);

		} else {

			return $attEmployeeData;
		}
	}
}
