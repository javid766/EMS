<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Weekday;
use App\Models\HrPayroll\Setup\AttGroup;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class WeekdayController extends Controller
{

    public $attWeekdayModel;
    public $utilsModel;
    public $attAttGroupModel;

    public function __construct() {

        $this->attWeekdayModel = new Weekday();
        $this->attAttGroupModel = new AttGroup();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){

        $weekdays = $this->getWeekDaysData();
        return view('hrpayroll.setup.weekday',compact('weekdays'));
    }

    public function getData($request, $id=0){

        return $this->attWeekdayModel->getWeekdays($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

    }

    public function fillGrid(Request $request){

        $weekdays = $this->getWeekDaysData();
        $modelData=$this->getData($request);
        foreach ($modelData as $key => $value) {
            if ($value['weeekday'] == 1) {
                $value['weeekday'] = $weekdays[1];
            }
            if ($value['weeekday'] == 2) {
                $value['weeekday'] = $weekdays[2];
            }
            if ($value['weeekday'] == 3) {
                $value['weeekday'] = $weekdays[3];
            }
            if ($value['weeekday'] == 4) {
                $value['weeekday'] = $weekdays[4];
            }
            if ($value['weeekday'] == 5) {
                $value['weeekday'] = $weekdays[5];
            }
            if ($value['weeekday'] == 6) {
                $value['weeekday'] = $weekdays[6];
            }
            if ($value['weeekday'] == 7) {
                $value['weeekday'] = $weekdays[7];
            }
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

        $modelData=$this->getData($request, $id)[0];
        if($modelData){
            return response($modelData);
        }
        else{
            return response(array(
                'error' => 1,
                'data' => 'Weekday doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){

        $weekday = Weekday::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();

        if ($weekday) {

            return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
        }

        if($request->id){

            $id=$request->id;

            return $this->attWeekdayModel->updateWeekday($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

        } else {

            return $this->attWeekdayModel->createWeekday($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){

        return $this->attWeekdayModel->deleteWeekday($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function getAttGroupsData(Request $request, $id=0){

        $attGroupsData = array();
        $attGroups = $this->attAttGroupModel->getAttGroups($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        foreach ($attGroups as $key => $value) {
            $attGroupsData[$value['id']] = $value['vname'];
        }

        return $attGroupsData;
    }
    public function getWeekDaysData(){

        $weekdays = array('1'  => 'Sunday','2'  => 'Monday','3'  => 'Tuesday','4'  => 'Wednesday','5'  => 'Thursday','6'  => 'Friday', '7'  => 'Saturday');
        return $weekdays;
    }

}
