<?php

namespace App\Models\HrPayroll\Setup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;
use App\Models\Utils;

class SaleTypes extends Model
{
    use HasFactory;
    protected $table = 'att_setup_sale_types';
	public $timestamps = false;

	public $utilsModel;

	public $PAGE_LINK = '/setup/sale-types';

	public function __construct() {

		$this->utilsModel = new Utils();
	}

	public function getSaleTypes($request, $id, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$validator = Validator::make($request->all() ,[
				'userid' => 'required | integer',
				'companyid' => 'required | integer',

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

		$saleTypes = SaleTypes::hydrate(
			DB::select('CALL sp_att_setup_sale_types_get('. $id .', '. $userid .', '. $companyid .')')
			
		);

		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Local Sale Types", "ErrorMsg"=>"CALL sp_att_setup_sale_types_get($id,$userid,$companyid)");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */


		if ($type == $this->utilsModel->CALL_TYPE_API) {

			return response([
				'SaleTypes' => $saleTypes,
				'status' => 'success'
			]);

		} else {

			return $saleTypes;
		}

	}

	public function createSaleTypes($request, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = 0;

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_CREATE, $type);

		if ($id > 0) {

			return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Sale Types created successfully', 'id', $id, $type, $this->PAGE_LINK);
		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', 'There is an error creating Sale Types.', $type, $this->PAGE_LINK);
		}
	}

	public function updateSaleTypes($request, $id, $type) {

		$validator = $this->validateCreateUpdateParams($request, $type);

		if($validator->fails()) {

			return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
		}

		$id = $this->createUpdate($request, $id, $this->utilsModel->SP_ACTION_UPDATE, $type);

		return $this->utilsModel->returnResponseStatusMessageExtra('success', 'Sale Types updated successfully', 'id', $id, $type, $this->PAGE_LINK);
	}

	public function deleteSaleTypes($id, $type) {

		if ($id > 0) {

			$action_type = $this->utilsModel->SP_ACTION_DELETE;
			$result = DB::select('CALL sp_att_setup_sale_types_insertupdate(
				?, 0, 0, 0, 0, 0, 0, 0, 0,
				"'. $this->utilsModel->SP_ACTION_DELETE .'")',
				[
					$id
				]
			)[0];

			/* Logs for stored procedure starts */
			$logData = array('LogName'=>"Local Sale Types", "ErrorMsg"=>"SET @id = $id; CALL sp_att_setup_sale_types_insertupdate(@id, 0, 0, 0, 0, 0, 0, 0, 0, '$action_type')");

			$this->utilsModel->saveDbLogs($logData);

			/* Logs for stored procedure ends */

			if ($result->status == $this->utilsModel->API_VALIDATION_ERROR) {
				return $this->utilsModel->returnResponseStatusMessage('error', $result->msg, $type, $this->PAGE_LINK);
			}

			return $this->utilsModel->returnResponseStatusMessage('success', 'Sale Types deleted successfully', $type, $this->PAGE_LINK);

		} else {

			return $this->utilsModel->returnResponseStatusMessage('error', "Sale Types id is required", $type, $this->PAGE_LINK);
		}
	}

	public function validateCreateUpdateParams($request, $type) {

		return Validator::make($request->all() ,[
			'vcode' => 'required ',
			'vname'	  => 'required ',
			'companyid'   => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			// 'isactive'		  => 'required | integer',
			'insertedby'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'insertedip'  => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
			'updatedby'	  => $type == $this->utilsModel->CALL_TYPE_API ? 'required | integer' : '',
			'updatedip'	  => $type == $this->utilsModel->CALL_TYPE_API ? 'required' : '',
		]);
	}

	public function createUpdate($request, $id, $sp_type, $type) {

		if ($type == $this->utilsModel->CALL_TYPE_API) {

			$insertedBy = $request->insertedby;
            $insertedIp = $request->insertedip;
            $updatedBy = $request->updatedby;
            $updatedIp = $request->updatedip;
            $companyid = $request->companyid;

		} else {

			$insertedBy = Auth::id();
			$insertedIp = $request->ip();
			$updatedBy = Auth::id();
			$updatedIp = $request->ip();
			$companyid = $request->session()->get('companyid', 0);
		}

		$vname = trim($request->vname);
		$is_active = (isset($request->isactive) ? $request->isactive : 0);

		if($sp_type == 'u'){
			$set_id = "SET @id = $id;";
		}else{
			$set_id = "";
		}


		/* Logs for stored procedure starts */
		$logData = array('LogName'=>"Local Sale Types", "ErrorMsg"=>"$set_id CALL sp_att_setup_sale_types_insertupdate(@id, '$request->vcode', '$vname',$companyid,  $is_active, $insertedBy, '$insertedIp', $updatedBy, '$updatedIp', '$sp_type')");

		$this->utilsModel->saveDbLogs($logData);

		/* Logs for stored procedure ends */

		return DB::select('CALL sp_att_setup_sale_types_insertupdate(
			?,
			"'. $request->vcode .'",
			"'. $vname .'",
			'. $companyid .',
			'. $is_active .',
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
