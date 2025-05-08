<?php

namespace App\Models\HrPayroll\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class Announcement extends Model
{
    use HasFactory;
    protected $table = '';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getAnnouncements($request, $id, $type) {

		$validator = Validator::make($request->all() ,[
			'datein' => 'required',
			'dateout' => 'required',
			'userid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'companyid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'locationid' => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
		]);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationid = $request->locationid;
			$employeeid = $request->employeeid;

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
			$employeeid = $request->session()->get('employeeid', 0);
		}

		$datein = date('Y-m-d', strtotime($request->datein));
		$dateout = date('Y-m-d', strtotime($request->dateout));

		$announcements = self::hydrate(DB::select('CALL sp_att_announcement_get('. $id .', "'. $datein .'", "'. $dateout .'", '. $userid .', '. $companyid .', '. $locationid .')'));

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'announcements' => $announcements,
				'status' => 'success'
			]);

		} else {

			return $announcements;
		}
	}
}
