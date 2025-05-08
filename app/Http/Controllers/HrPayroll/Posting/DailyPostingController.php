<?php

namespace App\Http\Controllers\HrPayroll\Posting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\HrPayroll\Setup\EType;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\Posting\DailyPosting;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class DailyPostingController extends Controller
{
    public $ETypeModel;
    public $deptModel;
    public $employeeInfoModel;
    public $dailyPostingModel;
    public $utilsModel;

    public function __construct() {

        $this->ETypeModel = new EType();
        $this->deptModel = new Dept();
        $this->employeeInfoModel  = new EmployeeInfo();
        $this->dailyPostingModel = new DailyPosting();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){
        $depts = $this->getDepartments($request);
        $eTypes      = $this->getETypes($request);
        return view('hrpayroll.posting.daily_posting',compact('depts','eTypes'));
    }

    public function fillGrid(Request $request, $deptid, $etypeid, $id=0){

        $request->request->add(['isactive' => '1']); //add request
        if($deptid == -1){
            $modelData = $this->employeeInfoModel->getEmployees($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('etypeid' , $etypeid);
            $modelData = $modelData->sort();
        }
        else{
            $modelData = $this->employeeInfoModel->getEmployees($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('deptid' , $deptid)->where('etypeid' , $etypeid);
            $modelData = $modelData->sort();
        }


        return Datatables::of($modelData)
        ->addColumn('checkboxes', function($data){
            return '<input type="checkbox" name="dtcheckbox[]" class="dtcheckbox" value="'.$data->id.'"/>';                
        })
        ->rawColumns(['checkboxes'])
        ->make(true);
    }

    public function save(Request $request){
        
        if (($request->dateto >= date('Y-m-d')) || ($request->datefrom >= date('Y-m-d'))) {

            return redirect()->back()->withInput($request->input())->with('error', 'You Cannot Post Attendense for Currenct and Future.');
        }

        $cwhere = '';


        if ($request->deptid != null && $request->deptid > 0) {

            $cwhere .= ' AND H.deptid='.$request->deptid;
        }

        if ($request->etypeid != null && $request->etypeid > 0) {

            $cwhere .= ' AND H.etypeid='.$request->etypeid;
        }

        if ($request->dtcheckbox != null && count($request->dtcheckbox) > 0) {

            $formattedIds = implode(',', $request->dtcheckbox);
            $cwhere .= ' AND H.id IN ('.$formattedIds.')';
        }


        $request['cwhere'] = $cwhere;
        
        $message = $this->dailyPostingModel->postAttendance($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        $m = json_decode($message);

        if($m[0]->message == null){
            // return redirect()->back()->withInput($request->input())->with('success' ,'Attendance Posted Successfully !!!');
             return redirect()->back()->withInput($request->input())
            ->with([
                
                'q_dateto' => $request->dateto
            ])->with('success' ,'Attendance Posted Successfully !!!');
        }
        else{
          return redirect()->back()->withInput($request->input())->with('error' , $m[0]->message);
        }    
    }


   public function getDepartments(Request $request, $id=0){
      
        $deptsData = $this->deptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($deptsData);
        return $deptsData;
    }

    public function getETypes(Request $request, $id=0){
        $etypes = $this->ETypeModel->getETypes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($etypes);
        return $etypes;
    }
}
