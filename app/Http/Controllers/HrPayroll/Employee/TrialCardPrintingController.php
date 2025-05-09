<?php

namespace App\Http\Controllers\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\HrPayroll\Setup\EType;
use App\Models\HrPayroll\Setup\Location;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class TrialCardPrintingController extends Controller
{
    public $deptModel;
    public $locationModel;
    public $eTypeModel;
    public $attHolidayModel;
    public $utilsModel;

    public function __construct() {
        $this->deptModel = new Dept();
        $this->locationModel = new Location();
        $this->eTypeModel = new EType();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){
        $allDepts = $this->getDepartments($request);
        $allLocations  = $this->getLocations($request);
        $allEtypes  = $this->getETypes($request);
        return view('hrpayroll.employee.trial_card_printing',compact('allDepts','allLocations','allEtypes'));
    }

    public function getData($request, $id=0){

        return $this->attHolidayModel->getHolidays($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

    }

    public function fillGrid(Request $request){

        $modelData=$this->getData($request);
        return Datatables::of($modelData)
        ->addColumn('action', function($data){
            return ('<div class="table-actions">
                <a class="btn grid-action-btns btn-info" id ="editBtn" href="#" data="'.$data['id'].'">Edit</a>
                <a class="btn grid-action-btns btn-danger"  href="'.url('attendance/setup/holiday/delete/'.$data['id']).'">Delete</a> 
                </div>');
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function save(Request $request){

        if($request->id){

            $id=$request->id;

            return $this->attHolidayModel->updateHoliday($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
            
        } else {

            return $this->attHolidayModel->createHoliday($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){

        return $this->attHolidayModel->deleteHoliday($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function getDepartments(Request $request, $id=0){

        $deptArr = array();
        $deptsData = $this->deptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        foreach ($deptsData as $key => $value) {
           $deptArr[$value['id']] = $value['vname'];    
       }

       return $deptArr;
    }

    public function getLocations(Request $request, $id=0){

        $locationArr = array();
        $locationData = $this->locationModel->getLocations($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        foreach ($locationData as $key => $value) {
           $locationArr[$value['id']] = $value['vname'];
           
       }

       return $locationArr;
    }

    public function getETypes(Request $request, $id=0){

        $eTypeArr = array();
        $eTypeData = $this->eTypeModel->getETypes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        foreach ($eTypeData as $key => $value) {
           $eTypeArr[$value['id']] = $value['vname'];
           
       }
       return $eTypeArr;
    }

}
