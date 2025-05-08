<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Utils;
use Auth;
use App\User;

class UserCompany extends Model
{
    use HasFactory;

    protected $table = 'user_company';
    public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/user/company/usercompany';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getUserCompanies($request, $id, $company) {
		
		if ($company == $this->utilsModel->CALL_TYPE_API) {

			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				//'companyid' => 'required | integer',
			]);

			if($validator->fails()) {

				return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $company, $this->PAGE_LINK);
			}

			$userid = $request->userid;
			$companyid = $request->companyid;
			$locationId = $request->locationid;

		} else {


			$userid = isset($request->userid) ? $request->userid : Auth::id();

			$companyid = $request->session()->get('companyid', 0);
			$locationId = $request->session()->get('locationid', 0);
		}

		// if ($companyid == 0) {

		// 	return $this->utilsModel->returnResponseStatusMessage('error', "Session expired please login again", $company, '/login');
		// }
		
		$userCompanies = UserCompany::hydrate(
			DB::select('CALL sp_user_company_get('. $id .', '. $userid .', '. $companyid .', '. $locationId .')')
		);

		if ($company == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'user_companies' => $userCompanies,
				'status' => 'success'
			]);

		} else {

			return $userCompanies;
		}

	}

	public function createUserCompany($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'User company created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating user company.', $type, $this->PAGE_LINK);
		}
	}

	public function updateUserCompany($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'User company updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteUserCompany($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_user_company_insertupdate(
				?, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			return $this->utilsModel->returnResponseStatusMessage('success', 'User company deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "User company id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'userid' 		=> 'required ',
			'companyid' 	=> 'required | integer',
			'tid' 			=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'projectid' 	=> 'required | integer',
			'soudu_name' 	=> 'required',
			'soudu_email' 	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'soudu_phoneno' => 'required',
			// 'isdefault' 	=> 'required | integer',
			// 'isactive'		  => 'required | integer',
			'insertedby'	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip'	=> $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'		=> $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'		=> $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
		]);
	}

	public function createUpdate($request, $id, $sp_type, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$insertedBy = $request->insertedby;
            $insertedIp = $request->insertedip;
            $updatedBy = $request->updatedby;
            $updatedIp = $request->updatedip;
            
            $tid = $request->tid;

		} else {

			$insertedBy = Auth::id();
			$insertedIp = $request->ip();
			$updatedBy = Auth::id();
			$updatedIp = $request->ip();
			
			$tid = Auth::user()->tid;
		}

		return DB::select('CALL sp_user_company_insertupdate(
			?,
			'. $request->userid .',
			'. $request->companyid .',
			'. $tid .',
			'. $request->projectid .',
			"'. $request->soudu_name .'",
			"'. (isset($request->soudu_email) ? $request->soudu_email : '') .'",
			"'. $request->soudu_phoneno .'",
			'. (isset($request->isdefault) ? $request->isdefault : 0) .',
			'. (isset($request->isactive) ? $request->isactive : 0) .',
			'.  $insertedBy  .',
			"'. $insertedIp .'",
			'.  $updatedBy .',
			"'. $updatedIp .'",
			"'. $sp_type .'")',
			[
				$id
			]
		)[0]->id;
	}

    public function user() {
    	return $this->belongsTo(User::class);
    }
}
