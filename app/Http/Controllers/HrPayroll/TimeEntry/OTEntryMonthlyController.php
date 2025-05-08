<?php

namespace App\Http\Controllers\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utils;
use App\Models\HrPayroll\TimeEntry\OTEntryMonthly;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\HrPayroll\Setup\EType;
use App\User;
use DataTables,Auth;

class OTEntryMonthlyController extends Controller
{
	public $otMonthlyModel;
	public $deptModel;
	public $attEtypeModel;
	public $utilsModel;

	public function __construct() {

		$this->otMonthlyModel = new OTEntryMonthly();
		$this->deptModel  = new Dept();
		$this->attEtypeModel = new EType();
		$this->utilsModel = new Utils();
	}

	public function index(Request $request){

		$departments = $this->getDepartments($request);
		$eTypes 	 = $this->getETypes($request);
		return view('hrpayroll.timeentry.ot_entry_monthly',compact('departments','eTypes'));
	}

	public function getData($request, $id=0){

		return $this->otMonthlyModel->getOTEntriesMonthly($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

	}
	public function search(Request $request){
		$deptid  = (int)$request->get('deptid');
		$alldepts  = $request->get('alldepts') ;
		$etypeid  = (int)$request->get('etypeid');
		$vdate  = $request->get('vdate');
		$request['employeeid'] = 0;

		//first date of the month
		$first_date_find = strtotime(date("Y-m-d", strtotime($vdate)) . ", first day of this month");
		$first_date = date("Y-m-d",$first_date_find);
		$request->request->add(['datein' => $first_date]);
		
		//last date of the month
		$last_date_find = strtotime(date("Y-m-d", strtotime($vdate)) . ", last day of this month");
		$last_date = date("Y-m-d",$last_date_find);
		$request->request->add(['dateout' => $last_date]);

		$data = $this->getData($request);
		/*Frontend filter */
		// foreach($data as $key => $entry){  
		// 	$entry['vdate'] = $vdate;
		// 	if ($deptid) {
		// 		if($entry->deptid != $deptid){ 
		// 			unset($data[$key]);
		// 		}
		// 	} 
		// 	if ($etypeid > 0) {

		// 		if($entry->etypeid != $etypeid){ 
		// 			unset($data[$key]);
		// 		} 
		// 	}           
		// }

		/*Frontend sorting */
		// $data = $data->sortBy('empcode');
		$userid = Auth::id();
            $datein = $first_date;
        $dateout = $last_date;
        // return redirect()->back()->withInput($request->input())->with(['data' => $data]);
        // return redirect()->back()->withInput($request->input())->with(['data' => $data,'empid'=>$employeeid,'deptid'=>$deptid,'vdate'=>$datein,'spname'=>'sp_att_timeentry_month_days_attendance_get']);
         return redirect()->back()->withInput($request->input())
            ->with([
                'data' => $data,
                'q_empid' => $request['employeeid'],
                'q_datein' => $datein,
                'q_dateout' => $dateout,
            ]);
        return redirect()->back()->withInput($request->input())->with(['data' => $data,'empid'=>$request['employeeid'],'deptid'=>$deptid,'vdate'=>$datein,'dateout'=>$dateout,'userid'=>$userid,'companyid'=>$companyid,'locationid'=>$locationid,'spname'=>'sp_att_timeentry_ot_entry_monthly_get']);
		
	}

	public function save(Request $request){
		$empGridData = $request->all();    
		$result = '';
		if ($request->get('data') == null) {
			return redirect()->back()->withInput($request->input())->with('success' , "Nothing to change ");
		}
		else{
			foreach ($empGridData['data'] as  $value) {
				$id = $value['id'];
				$first_date_find = strtotime(date("Y-m-d", strtotime($value['vdate'])) . ", first day of this month");
				$first_date = date("Y-m-d",$first_date_find);
				$value['vdate'] = $first_date;
				if (array_key_exists("overtime",$value)){
					$_request = $request;
					$_request->request->add($value);
					if ($id >0) {
						$result = $this->otMonthlyModel->updateOTEntryMonthly($_request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
					}
					else{
						if($value['overtime'] != "0.00"){
							$result = $this->otMonthlyModel->createOTEntryMonthly($_request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
						}
					}
				}
			}
			if ($result) {
            	return $result;
        	}
        	else{
            	return redirect()->back()->withInput($request->input())->with('success' , "Nothing to change."); 
        	} 
		}

	}
	public function getDepartments(Request $request, $id=0){

		$deptsData = $this->deptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($deptsData);
        return $deptsData;
	}

	public function getETypes(Request $request, $id=0){
		$eTypesData = $this->attEtypeModel->getETypes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($eTypesData); 
        return $eTypesData;
	}

}
