<?php

namespace App\Models\HrPayroll\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Utils;

class Tenant extends Model
{
    use HasFactory;

    protected $table = 'setup_tenant';

    protected $fillable = ['tpassword'];

    public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/setup/tenant';

	public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

		$this->utilsModel = new Utils();
	}

	public function getTenants($request, $id, $type) {

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

		// if ($companyid == 0) {

		// 	return $this->utilsModel->returnResponseStatusMessage('error', "Session expired please login again", $type, '/login');
		// }

		$tenants = Tenant::hydrate(
			DB::select('CALL sp_setup_tenant_get('. $id .', '. $userid .', '. $companyid .', '. $locationid .')')
		);

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'tenants' => $tenants,
				'status' => 'success'
			]);

		} else {

			return $tenants;
		}

	}

	public function createTenant($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $this->utilsModel->SP_ACTION_CREATE, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Tenant created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating tenant.', $type, $this->PAGE_LINK);
		}
	}

	public function updateTenant($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $this->utilsModel->SP_ACTION_UPDATE, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Tenant updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteTenant($id, $type) {

		if ($id > 0) {

			$result = DB::select('CALL sp_setup_tenant_insertupdate(
				?, 0, 0, 0, 0, 0, 0, 0, 0, NOW(), 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			);

			return $this->utilsModel->returnResponseStatusMessage('success', 'Tenant deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Tenant id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $sp_type, $type) {

		return Validator::make($request->all() ,[
			'vcode'			 => 'required',
			'vname'			 => 'required',
			'countryname'	 => 'required',
			'currnecyname'	 => 'required',
			'company_nature' => 'required',
			'url' 			 => 'required',
			'tlogin' 		 => 'required' . $sp_type == $this->utilsModel->SP_ACTION_CREATE ? ' | unique:setup_tenant' : '',
			'tpassword' 	 => 'required',
			'logindate' 	 => 'required',
			//'tisactive' 	 => 'required | integer',
			// 'isactive'		  => 'required | integer',
			'insertedby'	 => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip'	 => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'		 => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'		 => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
		]);
	}

	public function createUpdate($request, $id, $sp_type, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$insertedBy = $request->insertedby;
            $insertedIp = $request->insertedip;
            $updatedBy = $request->updatedby;
            $updatedIp = $request->updatedip;

		} else {

			$insertedBy = Auth::id();
			$insertedIp = $request->ip();
			$updatedBy = Auth::id();
			$updatedIp = $request->ip();
		}

		return DB::select('CALL sp_setup_tenant_insertupdate(
			?,
			"'. $request->vcode .'",
			"'. trim($request->vname) .'",
			"'. $request->countryname .'",
			"'. $request->currnecyname .'",
			"'. $request->company_nature .'",
			"'. $request->url .'",
			"'. $request->tlogin .'",
			"'. Hash::make($request->tpassword) .'",
			"'. date('Y-m-d H:m:s', strtotime($request->logindate)) .'",
			'. 1 .',
			'. 1 .',
			'.  $insertedBy  .',
			"'. $insertedIp .'",
			'. $updatedBy .',
			"'. $updatedIp .'",
			"'. $sp_type .'")',
			[
				$id
			]
		)[0]->id;
	}
}
