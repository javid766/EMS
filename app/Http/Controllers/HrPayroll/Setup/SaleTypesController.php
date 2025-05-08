<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utils;
use App\User;
use DataTables,Auth;
use App\Models\HrPayroll\Setup\SaleTypes;

class SaleTypesController extends Controller
{

    public $saleTypesModel;
    public $utilsModel;

    public function __construct() {

        $this->saleTypesModel = new SaleTypes();
        $this->utilsModel = new Utils();
    }

    public function index(){
        return view('hrpayroll.setup.sale_types');
    }


    public function getData($request, $id=0){
        return $this->saleTypesModel->getSaleTypes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
         
    }

    public function fillGrid(Request $request){
        
        $modelData=$this->getData($request);
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
                'data' => 'Sale Types doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){

        $bank = SaleTypes::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();

        if ($bank) {

            return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
        }
        
        if($request->id){
            
            $id=$request->id;

            return $this->saleTypesModel->updateSaleTypes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        
        } else {            

            return $this->saleTypesModel->createSaleTypes($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){

        return $this->saleTypesModel->deleteSaleTypes($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

}
