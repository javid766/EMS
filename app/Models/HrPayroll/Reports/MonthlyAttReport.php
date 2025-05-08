<?php

namespace App\Models\HrPayroll\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Utils;

class MonthlyAttReport extends Model
{
    use HasFactory;
    protected $table = 'att_employee';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/reports/monthy-attendence-report';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getAttMonthly($request, $id, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				'companyid' => 'required | integer',
				'locationid' => 'required | integer'

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
		$attMonthlyData = '';	
		// dd('CALL sp_rpt_att_m_card('. $userid .', '. $companyid .','. $locationid .' , \''.$request->cwhere .' \',"'. $request->datefrom .'","'. $request->dateto .'")');
		if ($request->attfilter == 'attcard') {
			$attMonthlyData = DB::select('CALL sp_rpt_att_m_card('. $userid .', '. $companyid .','. $locationid .' , \''.$request->cwhere .' \',"'. $request->datefrom .'","'. $request->dateto .'")');


			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Monthly Attendance Report", "ErrorMsg"=>"CALL sp_rpt_att_m_card($userid, $companyid, $locationid, '".$request->cwhere."', '".$request->datefrom."', '".$request->dateto."' )");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */

		}
	
		if ($request->attfilter == 'absenteelist') {
			$attMonthlyData = DB::select('CALL sp_rpt_att_m_absentees('. $userid .', '. $companyid .','. $locationid .' , \''.$request->cwhere .' \',"'. $request->datefrom .'","'. $request->dateto .'")');

			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Monthly Attendance Report", "ErrorMsg"=>"CALL sp_rpt_att_m_absentees($userid, $companyid, $locationid, '".$request->cwhere."', '".$request->datefrom."', '".$request->dateto."' )");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */

		}
		if ($request->attfilter == 'attmsummary') {
			$attMonthlyData = DB::select('CALL sp_rpt_att_m_summary('. $userid .', '. $companyid .','. $locationid .' , \''.$request->cwhere .' \',"'. $request->datefrom .'","'. $request->dateto .'")');

			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Monthly Attendance Report", "ErrorMsg"=>"CALL sp_rpt_att_m_summary($userid, $companyid, $locationid, '".$request->cwhere."', '".$request->datefrom."', '".$request->dateto."' )");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */


		}

		if ($request->attfilter == 'attlogs') {
			$attMonthlyData = DB::select('CALL sp_rpt_att_m_attendance('. $userid .', '. $companyid .','. $locationid .' , \''.$request->cwhere .' \',"'. $request->datefrom .'","'. $request->dateto .'")');

			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Monthly Attendance Report", "ErrorMsg"=>"CALL sp_rpt_att_m_attendance($userid, $companyid, $locationid, '".$request->cwhere."', '".$request->datefrom."', '".$request->dateto."' )");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */

		}
		if ($request->attfilter == 'leavelist') {
			$attMonthlyData = DB::select('CALL sp_rpt_att_m_leave('. $userid .', '. $companyid .','. $locationid .' , \''.$request->cwhere .' \',"'. $request->datefrom .'","'. $request->dateto .'")');

			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Monthly Attendance Report", "ErrorMsg"=>"CALL sp_rpt_att_m_leave($userid, $companyid, $locationid, '".$request->cwhere."', '".$request->datefrom."', '".$request->dateto."' )");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */
			
		}
		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'MonthlyAttReport' => $attMonthlyData,
				'status' => 'success'
			]);

		} else {

			return $attMonthlyData;
		}

	}
}
