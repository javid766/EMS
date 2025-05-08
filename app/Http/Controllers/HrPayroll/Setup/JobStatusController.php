<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\JobStatus;
use App\Models\Utils;
use DataTables,Auth;

class JobStatusController extends Controller
{
    public $attJobStatusModel;
    public $utilsModel;

    public function __construct() {

        $this->attJobStatusModel = new JobStatus();
        $this->utilsModel = new Utils();

    }

    public function index(){

        return view('hrpayroll.setup.job_status');
    }

    public function getData($request, $id=0){
           
        return $this->attJobStatusModel->getJobStatuses($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
         
    }

    public function fillGrid(Request $request){
        
        $modelData=$this->getData($request);
        return Datatables::of($modelData)
        ->addColumn('action', function($data){
            return ('<div class="table-actions">
                <a class="btn grid-action-btns btn-info" id ="editBtn" href="#" data="'.$data['id'].'">Edit</a>
               <a class="btn grid-action-btns btn-danger" id ="deleteBtn" href="#" data="'.$data['id'].'" data-toggle="modal" data-target="#deleteModal">Delete</a> 
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
                'data' => 'Location doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){

        $jobStatus = JobStatus::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();

        if ($jobStatus) {

            return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
        }
        
        if($request->id){
            
            $id=$request->id;

            return $this->attJobStatusModel->updateJobStatus($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        
        } else {

            return $this->attJobStatusModel->createJobStatus($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){

        return $this->attJobStatusModel->deleteJobStatus($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

}
