<?php

namespace App\Http\Controllers\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utils;
use App\Models\HrPayroll\TimeEntry\OTEntryDaily;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\HrPayroll\Setup\EType;
use App\User;
use DataTables,Auth;

class OTEntryDailyController extends Controller
{	
	public $OTEntryDailyModel;
	public $deptModel;
	public $attEtypeModel;
	public $utilsModel;

	public function __construct() {
		$this->OTEntryDailyModel = new OTEntryDaily();
		$this->deptModel  = new Dept();
		$this->attEtypeModel = new EType();
		$this->utilsModel = new Utils();
	}

	public function index(Request $request){

		$departments = $this->getDepartments($request);
		$eTypes 	 = $this->getETypes($request);
		return view('hrpayroll.timeentry.ot_entry_daily',compact('departments','eTypes'));
	}

	public function getData($request, $id=0){

		return $this->OTEntryDailyModel->getOTEntryDaily($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
	}

	public function search(Request $request){

		$deptid  = (int)$request->get('deptid');
		$alldepts  = $request->get('alldepts') ;
		$etypeid  = (int)$request->get('etypeid');
		$vdate    = $request->get('vdate');
		$request->request->add(['datein' => $vdate]);
		$request['employeeid'] = 0;
		$filldate = date('d/m/Y',strtotime($vdate));
		$data = $this->getData($request);
		
		/* Frontend filters */
		// foreach($data as $key => $entry){ 
		// 	$entry['filldate'] = $filldate; 
		// 	$entry['vdate'] = $vdate; 

		// 	if (isset($request->otonly) ) {
		// 		if($entry->overtime == "0.00"){
		// 			unset($data[$key]);
		// 		}
		// 	}
			
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
		/* Frontend soritng */
		// $data = $data->sortBy('empcode');

		
		$userid = Auth::id();
            $datein = date('Y-m-d 00:00:00', strtotime($request->vdate));
        $dateout = date('Y-m-d 00:00:00', strtotime($request->vdate . ' +1 day'));
        return redirect()->back()->withInput($request->input())
            ->with([
                'data' => $data,
                'q_empid' => $request['employeeid'],
                'q_datein' => $datein,
                'q_dateout' => $dateout,
            ]);
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
				if (array_key_exists("overtime",$value)){
					$_request = $request;
					$_request->request->add($value);
					if ($id >0) {
						$result = $this->OTEntryDailyModel->updateOTEntryDaily($_request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
					}
					elseif ($id <= 0 ){
						if($value['overtime'] != "0.00"){
							$result = $this->OTEntryDailyModel->createOTEntryDaily($_request, $this->utilsModel->CALL_TYPE_DEFAULT);
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
