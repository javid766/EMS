<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utils;
use App\User;
use DataTables,Auth;
use App\Models\HrPayroll\Setup\SalaryTaxSlab;

class SalaryTaxSlabController extends Controller
{

    public $salaryTaxSlabModel;
    public $utilsModel;

    public function __construct() {

        $this->salaryTaxSlabModel = new SalaryTaxSlab();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){
        return view('hrpayroll.setup.salarytaxslab');
    }


    public function getData($request, $id=0){
           
        return $this->salaryTaxSlabModel->getSalaryTaxSlabs($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
         
    }

    public function fillGrid(Request $request){
        
        $modelData = $this->getData($request);
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
        if($modelData){
            return response($modelData);
        }
        else{
            return response(array(
                'error' => 1,
                'data' => 'Salary Tax Slab doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){
        
        if($request->id){
            
            $id=$request->id;

            return $this->salaryTaxSlabModel->updateSalaryTaxSlab($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        
        } else {            

            return $this->salaryTaxSlabModel->createSalaryTaxSlab($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){

        return $this->salaryTaxSlabModel->deleteSalaryTaxSlab($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

}
