<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Shift;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class ShiftController extends Controller
{
    public $attShiftModel;
    public $utilsModel;

    public function __construct() {

        $this->attShiftModel = new Shift();
        $this->utilsModel = new Utils();

    }

    public function index(){
        return view('hrpayroll.setup.shift');
    }

    public function getData($request, $id=0){

        return $this->attShiftModel->getShifts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
         
    }

    public function fillGrid(Request $request){
        
        $modelData=$this->getData($request);
        foreach ($modelData as $key => $value) {
            $value->timein = date('h:i A', strtotime($value->timein));
            $value->timeout = date('h:i A', strtotime($value->timeout));
            $value->resttimefrom = date('h:i A', strtotime($value->resttimefrom));
            $value->resttimeto = date('h:i A', strtotime($value->resttimeto));
            $value->relaxtime = date('h:i A', strtotime($value->relaxtime));
            $value->minatttime = date('h:i A', strtotime($value->minatttime));
            $value->minhdtime = date('h:i A', strtotime($value->minhdtime));
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
        $modelData->timein = str_replace(':', '.', $modelData->timein);
        $timein = date('H:i', strtotime($modelData->timein));
        $modelData->timein = preg_replace('/\s+/', 'T', $timein);

        $modelData->timeout = str_replace(':', '.', $modelData->timeout);
        $timeout = date('H:i', strtotime($modelData->timeout));
        $modelData->timeout = preg_replace('/\s+/', 'T', $timeout);


        $modelData->resttimefrom = str_replace(':', '.', $modelData->resttimefrom);
        $resttimefrom = date('H:i', strtotime($modelData->resttimefrom));
        $modelData->resttimefrom = preg_replace('/\s+/', 'T', $resttimefrom);

        $modelData->resttimeto = str_replace(':', '.', $modelData->resttimeto);
        $resttimeto = date('H:i', strtotime($modelData->resttimeto));
        $modelData->resttimeto = preg_replace('/\s+/', 'T', $resttimeto);

        $modelData->relaxtime = str_replace(':', '.', $modelData->relaxtime);
        $relaxtime = date('H:i', strtotime($modelData->relaxtime));
        $modelData->relaxtime = preg_replace('/\s+/', 'T', $relaxtime);

        $modelData->minatttime = str_replace(':', '.', $modelData->minatttime);
        $minatttime = date('H:i', strtotime($modelData->minatttime));
        $modelData->minatttime = preg_replace('/\s+/', 'T', $minatttime);

        $modelData->minhdtime = str_replace(':', '.', $modelData->minhdtime);
        $minhdtime = date('H:i', strtotime($modelData->minhdtime));
        $modelData->minhdtime = preg_replace('/\s+/', 'T', $minhdtime);
        
        if($modelData){
            return response($modelData);
        }
        else{
            return response(array(
                'error' => 1,
                'data' => 'Shifts doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){

        $request->timein = date('H:i', strtotime($request->timein));
        $request->timein = preg_replace('/\s+/', 'T', $request->timein);
        $request->timeout = date('H:i', strtotime($request->timeout));
        $request->timeout = preg_replace('/\s+/', 'T', $request->timeout);
        $request->resttimefrom = date('H:i', strtotime($request->resttimefrom));
        $request->resttimefrom = preg_replace('/\s+/', 'T', $request->resttimefrom);
        $request->resttimeto = date('H:i', strtotime($request->resttimeto));
        $request->resttimeto = preg_replace('/\s+/', 'T', $request->resttimeto);
        $request->relaxtime = date('H:i', strtotime($request->relaxtime));
        $request->relaxtime = preg_replace('/\s+/', 'T', $request->relaxtime);
        $request->minatttime = date('H:i', strtotime($request->minatttime));
        $request->minatttime = preg_replace('/\s+/', 'T', $request->minatttime);
        $request->minhdtime = date('H:i', strtotime($request->minhdtime));
        $request->minhdtime = preg_replace('/\s+/', 'T', $request->minhdtime);

        $request->timein = str_replace(':', '.', $request->timein);
        $request->timeout = str_replace(':', '.', $request->timeout);
        $request->workinghrs = str_replace(':', '.', $request->workinghrs);
        $request->resttimefrom = str_replace(':', '.', $request->resttimefrom);
        $request->resttimeto = str_replace(':', '.', $request->resttimeto);
        $request->relaxtime = str_replace(':', '.', $request->relaxtime);
        $request->minatttime = str_replace(':', '.', $request->minatttime);
        $request->minhdtime = str_replace(':', '.', $request->minhdtime);

        $vcode = Shift::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();

        if ($vcode) {

            return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
        }
            
        if($request->id){

            $id = $request->id;
            return $this->attShiftModel->updateShift($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        
        } else {

            return $this->attShiftModel->createShift($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){

        return $this->attShiftModel->deleteShift($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

}
