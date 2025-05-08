<?php

namespace App\Http\Controllers\HrPayroll\Setup\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\HrPayroll\Setup\DeptGroup;
use App\Models\HrPayroll\Setup\Desg;
use App\Models\HrPayroll\Setup\AttCode;
use App\Models\HrPayroll\Setup\Holiday;
use App\Models\HrPayroll\Setup\SalaryTaxSlab;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class SetupReportsController extends Controller
{
    public $deptModel;
    public $deptGroupModel;
    public $desgModel;
    public $attCodeModel;
    public $attHolidayModel;
    public $salaryTaxSlabModel;
    public $utilsModel;

    public function __construct() {

        $this->deptModel = new Dept();
        $this->deptGroupModel = new DeptGroup();
        $this->desgModel = new Desg();
        $this->attCodeModel = new AttCode();
        $this->attHolidayModel = new Holiday();
        $this->salaryTaxSlabModel = new SalaryTaxSlab();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){

        return view('hrpayroll.setup.reports.setup_reports');
    }

    public function fillGrid(Request $request,  $radiobtnID){
      
        $id = 0;
        if($radiobtnID == 1){
          $modelData = $this->deptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
        if($radiobtnID == 2){
          $modelData = $this->deptGroupModel->getDeptGroups($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
        if($radiobtnID == 3){
          $modelData = $this->desgModel->getDesgs($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
        if($radiobtnID == 4){
          $modelData = $this->attHolidayModel->getHolidays($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
        if($radiobtnID == 5){
          $modelData = $this->attCodeModel->getAttCodes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
        if($radiobtnID == 6){
          $modelData = $this->salaryTaxSlabModel->getSalaryTaxSlabs($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        }     
        return Datatables::of($modelData)
        ->make(true);
    }

}
