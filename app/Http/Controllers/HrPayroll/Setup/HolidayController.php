<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Holiday;
use App\Models\HrPayroll\Setup\AttGroup;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class HolidayController extends Controller
{
    public $attHolidayModel;
    public $attAttGroupModel;
    public $utilsModel;

    public function __construct() {

        $this->attHolidayModel = new Holiday();
        $this->attAttGroupModel = new AttGroup();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){

        $leavetypes = $this->getLeaveTypes($request);
        return view('hrpayroll.setup.holiday',compact('leavetypes'));
    }

    public function getData($request, $id=0){

        return $this->attHolidayModel->getHolidays($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);

    }

    public function fillGrid(Request $request){
        $modelData=$this->getData($request);
        foreach ($modelData as $value) {
             $value['vdate'] = date('d/m/Y', strtotime($value->vdate));
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
        $vdate = strtotime($modelData->vdate);
        $vdate = date('Y-m-d',$vdate); 
        $modelData['vdate']=$vdate;
        if($modelData){
            return response($modelData);
        }
        else{
            return response(array(
                'error' => 1,
                'data' => 'Holiday doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){

        $holiday = Holiday::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();
    
        if ($holiday) {

            return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
        }

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

    public function getLeaveTypes(Request $request, $id=0){

        $attGroups = $this->attAttGroupModel->getAttGroups($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=',1)->where('isleave','=',0)->pluck('vname','id')->toArray();
        natcasesort($attGroups);
        return $attGroups;
    }

}
