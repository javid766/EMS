<?php

namespace App\Http\Controllers\HrPayroll\TimeEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\TimeEntry\OfficialVisitEntry;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class OfficialVisitEntryController extends Controller
{
    public $employeeInfoModel;
    public $officialVEModel;
    public $utilsModel;

    public function __construct() {
        $this->officialVEModel = new OfficialVisitEntry();
        $this->employeeInfoModel = new EmployeeInfo();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){
        $employees = $this->getEmployees($request);
        return view('hrpayroll.timeentry.official_visit_entry',compact('employees'));
    }

    public function getData($request, $id=0){
        return $this->officialVEModel->getOfficialVisitEntries($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function fillGrid(Request $request){
        $modelData=$this->getData($request);
        foreach ($modelData as $value) {
             $value['vdatefrom'] = date('d/m/Y h:i A', strtotime($value->vdatefrom));
             $value['vdateto'] = date('d/m/Y  h:i A', strtotime($value->vdateto));
        }
        return Datatables::of($modelData)
        ->addColumn('action', function($data){
            return ('<div class="table-actions">
                <button id="editBtn" data="'.$data['id'].'" class="btn btn-info btn-icon"><i class="ik ik-edit"></i></button>

                <button id="deleteBtn" class="btn btn-danger btn-icon"  data="'.$data['id'].'" data-toggle="modal" data-target="#deleteModal"><i class="ik ik-trash-2"></i></button> 
                </div>');
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function fillForm(Request $request, $id){
        $modelData = $this->getData($request, $id)[0];

        $vdatefrom = date('Y-m-d H:i', strtotime($modelData->vdatefrom));
        $modelData['vdatefrom'] = preg_replace('/\s+/', 'T', $vdatefrom);
        $vdateto = date('Y-m-d H:i', strtotime($modelData->vdateto));
        $modelData['vdateto'] = preg_replace('/\s+/', 'T', $vdateto);

        if($modelData){
            return response($modelData);
        }
        else{
            return response(array(
                'error' => 1,
                'data' => 'Official Visit Entry doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){
        if($request->id){
           $officialVisitEntry = OfficialVisitEntry::where('appno', trim($request->appno))->where('id', '<>', $request->id)->first();
        } else {
            $officialVisitEntry = OfficialVisitEntry::where('appno', trim($request->appno))->first();
        }        
        if ($officialVisitEntry) {
            return redirect()->back()->withInput($request->input())->with('error', 'APP No. already exists.');
        }

        $request['vdatefrom'] = (str_replace("T", " ", $request->vdatefrom));
        $request['vdateto']   = (str_replace("T", " ", $request->vdateto));


        if($request->id){
            $id=$request->id;
            return $this->officialVEModel->updateOfficialVisitEntry($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        } else {
            return $this->officialVEModel->createOfficialVisitEntry($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){
        return $this->officialVEModel->deleteOfficialVisitEntry($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function getEmployees(Request $request, $id=0){
        $request->request->add(['isactive' => '1']); //add request
        $employeeInfoData = array();
        $employeeInfo = $this->employeeInfoModel->getEmployees($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        foreach ($employeeInfo as $value) {
             $employeeInfoData[$value['id']] = $value['employeename']." ".$value['fathername']." (".$value['empcode'] .") ";
        }
        natcasesort($employeeInfoData);
        return $employeeInfoData;
    }

}
