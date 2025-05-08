<?php

namespace App\Models\HrPayroll\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class LeaveType extends Model
{
    use HasFactory;

    protected $table = 'att_setup_leave_type';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/setup/leave-types';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getLeaveTypes($request, $id, $type) {

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

		$leaveTypes = LeaveType::hydrate(
			DB::select('CALL sp_att_setup_leave_type_get('. $id .', '. $userid .', '. $companyid .', '. $locationid .')')
		);

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'leave_types' => $leaveTypes,
				'status' => 'success'
			]);

		} else {

			return $leaveTypes;
		}

	}
}
