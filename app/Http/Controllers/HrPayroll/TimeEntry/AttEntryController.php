<?php

namespace App\Http\Controllers\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\HrPayroll\Setup\EType;
use App\Models\HrPayroll\Setup\Location;
use App\Models\HrPayroll\TimeEntry\AttEntry;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class AttEntryController extends Controller
{
    public $deptModel;
    public $eTypeModel;
    public $AttEntryModel;
    public $employeeInfoModel;
    public $utilsModel;

    public function __construct() {

        $this->deptModel = new Dept();
        $this->eTypeModel = new EType();
        $this->AttEntryModel = new AttEntry();
        $this->employeeInfoModel  = new EmployeeInfo();
        $this->utilsModel = new Utils();
    }

    public function search(Request $request){

        $etypeid = (int)$request->get('etypeId');
        $deptid  = (int)$request->get('deptid');
        $alldepts  = $request->get('alldepts');
        $datein  = $request->get('date');
        $dateout = date('Y-m-d',strtotime("+1 day", strtotime( $datein)));
        $inout = (int)$request->get('inout');

        $request->request->add([
            'shiftid' => 0,
            'employeeid' => 0,
            'datein'   => $datein,
            'dateout'  => $dateout,
            'inout'    => $inout,
            'deptid'   => $deptid,
            'etypeid'  => $etypeid
        ]);
        
        $data = $this->getData($request);

        /*Frontend sort */
        //$data = $data->sortBy('empcode');


        return redirect()->back()->withInput($request->input())
            ->with([
                'data' => $data,
                'q_empid' => '0',
                'q_datein' => $datein,
                'q_dateout' => $dateout,
                'q_ionut' => $inout,
                'q_deptid' => $deptid,
                'q_etypeid'  => $etypeid

            ]);
    }

    public function index(Request $request){

        $allDepts   = $this->getDepartments($request);
        $allEtypes  = $this->getETypes($request);

        return view('hrpayroll.timeentry.att_entry',compact('allDepts','allEtypes'));
    }

    public function getData($request, $id=0){

        return $this->AttEntryModel->getAttEntries($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

    }

    public function save(Request $request){
        $input = $request->all();
        if ($request->get('data') == null) {
            return redirect()->back()->withInput($request->input())->with('success' , "Nothing to change ");
        }
        else{
            $data = $input['data'];
            $count = 0;

            foreach ($data as $value){
                $_request = $request;
                $_request->request->add($value);
                if(max($value)=="on"){
                    $count ++;
                    $id=$request->id;
                    $datein=$request->datein;
                    $dateout=$request->dateout;

                    if($id <= 0){
                        if($datein != null && $datein != "00:00" && $dateout != "00:00"){
                            $result = $this->AttEntryModel->createAttEntry($_request, $this->utilsModel->CALL_TYPE_DEFAULT);
                        } else {
                            $count--;
                        }
                    }else{
                        if($datein== "00:00" && $dateout == "00:00"){
                         $result = $this->AttEntryModel->deleteAttEntry($id, $this->utilsModel->CALL_TYPE_DEFAULT);

                         }else{
                            $result = $this->AttEntryModel->updateAttEntry($_request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
                         }

                    }
                }
            }
            //dd($count);
            if($count>0){
                return $result;
            }
            else{
                return redirect()->back()->withInput($request->input())->with('success' , "Nothing to change ");
            }
         }   
    }

    public function delete($id){
        return $this->attHolidayModel->deleteHoliday($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function getDepartments(Request $request, $id=0){

        $deptsData =  $this->deptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($deptsData);
        return $deptsData;
    }

    public function getETypes(Request $request, $id=0){
        $eTypesData = $this->eTypeModel->getETypes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($eTypesData); 
        return $eTypesData;
    }

}
