<?php

namespace App\Models\HrPayroll\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Utils;

class DailyAttReport extends Model
{
    use HasFactory;
    protected $table = 'att_employee';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/reports/attendance-listing-report';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getAttListing($request, $id, $type) {
	
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

		$attListingData = '';	
		if ($request->attfilter == 'unpostedAtt') {		
			$attListingData = DailyAttReport::hydrate(
			DB::select('CALL sp_rpt_att_d_unposted('. $userid .', '. $companyid .','. $locationid .',"'. $request->vdate .'" , \''.$request->cwhere .' \')')
			);


			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Daily Attendance Report", "ErrorMsg"=>"CALL sp_rpt_att_d_unposted($userid, $companyid, $locationid, '".$request->vdate."', '".$request->cwhere."')");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */

		}

		if ($request->attfilter == 'postedAtt') {
			$attListingData = DailyAttReport::hydrate(
			DB::select('CALL sp_rpt_att_d_posted('. $userid .', '. $companyid .','. $locationid .',"'. $request->vdate .'" , \''.$request->cwhere .' \')')
			);

			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Daily Attendance Report", "ErrorMsg"=>"CALL sp_rpt_att_d_posted($userid, $companyid, $locationid, '".$request->vdate."', '".$request->cwhere."')");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */

		}

		if ($request->attfilter == 'unpostedAttSummary') {
			$attListingData = DailyAttReport::hydrate(
			DB::select('CALL sp_rpt_att_d_unposted_summary('. $userid .', '. $companyid .','. $locationid .',"'. $request->vdate .'" , \''.$request->cwhere .' \')')
			);

			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Daily Attendance Report", "ErrorMsg"=>"CALL sp_rpt_att_d_unposted_summary($userid, $companyid, $locationid, '".$request->vdate."', '".$request->cwhere."')");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */

		}

		if ($request->attfilter == 'postedAttSummary') {
			$attListingData = DailyAttReport::hydrate(
			DB::select('CALL sp_rpt_att_d_posted_summary('. $userid .', '. $companyid .','. $locationid .',"'. $request->vdate .'" , \''.$request->cwhere .' \')')
			);

			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Daily Attendance Report", "ErrorMsg"=>"CALL sp_rpt_att_d_posted_summary($userid, $companyid, $locationid, '".$request->vdate."', '".$request->cwhere."')");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */
		}

		if ($request->attfilter == 'absenteeList') {
			$attListingData = DailyAttReport::hydrate(
			DB::select('CALL sp_rpt_att_d_absenteelist('. $userid .', '. $companyid .','. $locationid .',"'. $request->vdate .'" , \''.$request->cwhere .' \')')
			);

			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Daily Attendance Report", "ErrorMsg"=>"CALL sp_rpt_att_d_absenteelist($userid, $companyid, $locationid, '".$request->vdate."', '".$request->cwhere."')");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */
			
		}


		if ($type == $this->utilsModel->CALL_TYPE_API) {
			return response([
				'attListingReport' => $attListingData,
				'status' => 'success'
			]);
		} else {
			return $attListingData;
		}

	}
}
