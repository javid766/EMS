<?php

namespace App\Http\Controllers\HrPayroll\Posting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class SalaryChequeController extends Controller
{
    public $deptModel;
    public $utilsModel;

    public function __construct() {

        $this->deptModel = new Dept();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){
        $depts = $this->getDepartments($request);
        $branches = array('Other','Cash');
        return view('hrpayroll.posting.salary_cheque',compact('depts','branches'));
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
        return $this->deptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->pluck('vname','id');
    }

}
