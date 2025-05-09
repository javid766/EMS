<?php

namespace App\Models\HrPayroll\Posting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class DailyPosting extends Model
{
    use HasFactory;
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/posting/daily-posting';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function postAttendance($request, $type) {

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

		} else {

			$userid = Auth::id();
			$companyid = $request->session()->get('companyid', 0);
			$locationid = $request->session()->get('locationid', 0);
		}

		$cwhere = $request->get('cwhere', '');
		$dateto = $request->dateto;
		
		$posting = DailyPosting::hydrate(
			DB::select('CALL sp_att_closing_day("'. $dateto .'", "'. $cwhere .'", '. $userid .', '. $companyid .', '. $locationid .')')
		);

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'posting' => $posting,
				'status' => 'success'
			]);

		} else {

			return $posting;
		}

	}
	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'dateto' => 'required ',
			'datefrom' => 'required ',
			'companyid'   => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			// 'isactive'		  => 'required | integer',
			'insertedby'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'	  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'	  => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
		]);
	}
}
