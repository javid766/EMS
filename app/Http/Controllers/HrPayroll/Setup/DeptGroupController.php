<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Utils;
use App\User;
use DataTables,Auth;
use App\Models\HrPayroll\Setup\DeptGroup;

class DeptGroupController extends Controller
{

    public $utilsModel;
    public $attDeptGroupModel;

    public function __construct() {

        $this->attDeptGroupModel = new DeptGroup();
        $this->utilsModel = new Utils();

    }

    public function index(){

        return view('hrpayroll.setup.dep_group');
    }

    public function getData($request, $id=0){
       
        return $this->attDeptGroupModel->getDeptGroups($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        
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
                'data' => 'Department Group doesn\'t exists!!',
            ));
        }

    }

    public function save(Request $request){

        $code = DeptGroup::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();

        if($code) {

            return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
        }
        
        if($request->SalariesPermanent != null){
            $SP = DB::table('view_acc_coa')->where('vcode', $request->SalariesPermanent)->pluck('id')->first();
            if(!$SP){
                  return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Salaries Permanent.');
            }
            else{ $request['SalariesPermanent']  = $SP; }
        }
        else{ $request['SalariesPermanent']  = 0; }


        if($request->AllowancePermanent != null){
            $AP = DB::table('view_acc_coa')->where('vcode', $request->AllowancePermanent)->pluck('id')->first();
            if(!$AP){
                  return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Allowance Permanent.');
            }
            else{ $request['AllowancePermanent']  = $AP; }

        }
        else{ $request['AllowancePermanent']  = 0; }


        if($request->AdvancePermanent != null){
            $ADP= DB::table('view_acc_coa')->where('vcode', $request->AdvancePermanent)->pluck('id')->first();
            if(!$ADP){
                return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Advance Permanent.');
            }
            else{ $request['AdvancePermanent']  = $ADP; }

        }
        else{ $request['AdvancePermanent']  = 0; }


        if($request->LoanPermanent != null){
            $LP = DB::table('view_acc_coa')->where('vcode', $request->LoanPermanent)->pluck('id')->first();
            if(!$LP){
                return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Loan Permanent.');
            }
            else{ $request['LoanPermanent']  = $LP; }
        }
        else{ $request['LoanPermanent']  = 0; }


        if($request->OverTimePermanent != null){
            $OTP = DB::table('view_acc_coa')->where('vcode', $request->OverTimePermanent)->pluck('id')->first();
            if(!$OTP){
                return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code OverTime Permanent.');
            }
            else{ $request['OverTimePermanent']  = $OTP; }
        }
        else{ $request['OverTimePermanent']  = 0; }
       
          
        if($request->TaxPermanent != null){
            $TP = DB::table('view_acc_coa')->where('vcode', $request->TaxPermanent)->pluck('id')->first();
            if(!$TP){
                return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Tax Permanent.');
            }
            else{ $request['TaxPermanent']  = $TP; }
        }
        else{ $request['TaxPermanent']  = 0; }


        if($request->OtherIncomePermanent != null){
            $OIP = DB::table('view_acc_coa')->where('vcode', $request->OtherIncomePermanent)->pluck('id')->first();
            if(!$OIP){
                return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Other Income Permanent.');
            }
            else{ $request['OtherIncomePermanent']  = $OIP; }
        }
        else{ $request['OtherIncomePermanent']  = 0; }
        
        
        if($request->SalaryPayablePermanent != null){
            $SPP = DB::table('view_acc_coa')->where('vcode', $request->SalaryPayablePermanent)->pluck('id')->first();
            if(!$SPP){
                return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Salary Payable Permanent.');
            }
            else{ $request['SalaryPayablePermanent']  = $SPP; }
        }
        else{ $request['SalaryPayablePermanent']  = 0; }
        
       
        if($request->EmployeePFPermanent != null){
            $PFP = DB::table('view_acc_coa')->where('vcode', $request->EmployeePFPermanent)->pluck('id')->first();
            if(!$PFP){
                return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Employee PF Permanent.');
            }
            else{ $request['EmployeePFPermanent']  = $PFP; }
        }
        else{ $request['EmployeePFPermanent']  = 0; }
        
        
        if($request->EmployerPFPermanent != null){
            $EPPF = DB::table('view_acc_coa')->where('vcode', $request->EmployerPFPermanent)->pluck('id')->first();
            if(!$EPPF){
                return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Employer PF Permanent.');
            }
            else{ $request['EmployerPFPermanent']  = $EPPF; }
        }
        else{ $request['EmployerPFPermanent']  = 0; }


        if($request->EmployeeEOBIPermanent != null){
            $EobiP = DB::table('view_acc_coa')->where('vcode', $request->EmployeeEOBIPermanent)->pluck('id')->first();
            if(!$EobiP){
                return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Employee EOBI Permanent.');
            }
            else{ $request['EmployeeEOBIPermanent']  = $EobiP; }
        }
        else{ $request['EmployeeEOBIPermanent']  = 0; }


        if($request->EmployerEOBIPermanent != null){
            $EobirP = DB::table('view_acc_coa')->where('vcode', $request->EmployerEOBIPermanent)->pluck('id')->first();
            if(!$EobirP){
                 return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Employer EOBI Permanent.');
            }
            else{ $request['EmployerEOBIPermanent']  = $EobirP; }
        }
        else{ $request['EmployerEOBIPermanent']  = 0; }
        
        
        if($request->SalariesContract != null){
            $SC = DB::table('view_acc_coa')->where('vcode', $request->SalariesContract)->pluck('id')->first();
            if(!$SC){
             return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Salaries Contract.');
            }
            else{ $request['SalariesContract']  = $SC; }
        }
        else{ $request['SalariesContract']  = 0; }
        

        if($request->AllowanceContract != null){
            $AC = DB::table('view_acc_coa')->where('vcode', $request->AllowanceContract)->pluck('id')->first();
            if(!$AC){
               return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Allowance Contract.');
            }
            else{ $request['AllowanceContract']  = $AC; }
        }
        else{ $request['AllowanceContract']  = 0; }
       
        if($request->AdvanceContract != null){
            $ADC = DB::table('view_acc_coa')->where('vcode', $request->AdvanceContract)->pluck('id')->first();
            if(!$ADC){
                return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Advance Contract.');
            }
            else{ $request['AdvanceContract']  = $ADC; }
        }
        else{ $request['AdvanceContract']  = 0; }
              

        if($request->LoanContract != null){
            $LC = DB::table('view_acc_coa')->where('vcode', $request->LoanContract)->pluck('id')->first();
            if(!$LC){
                return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Loan Contract.');
            }
            else{ $request['LoanContract']  = $LC; }
        }
        else{ $request['LoanContract']  = 0; } 
        
       
        if($request->OverTimeContract != null){
            $OTC = DB::table('view_acc_coa')->where('vcode', $request->OverTimeContract)->pluck('id')->first();
            if(!$OTC){
             return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Over Time Contract.');
            }
            else{ $request['OverTimeContract']  = $OTC; }
        }
        else{ $request['OverTimeContract']  = 0; } 
        
        
        if($request->TaxContract != null){
            $TC = DB::table('view_acc_coa')->where('vcode', $request->TaxContract)->pluck('id')->first();
            if(!$TC){
               return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Tax Contract.');
            }
            else{ $request['TaxContract']  = $TC; }
        }
        else{ $request['TaxContract']  = 0; } 
       
        
        if($request->OtherIncomeContract != null){
            $OIC = DB::table('view_acc_coa')->where('vcode', $request->OtherIncomeContract)->pluck('id')->first();
            if(!$OIC){
                return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Other Income Contract.');
            }
            else{ $request['OtherIncomeContract']  = $OIC; }
        }
        else{ $request['OtherIncomeContract']  = 0; } 
       
        
        if($request->SalaryPayableContract != null){
            $SPC = DB::table('view_acc_coa')->where('vcode', $request->SalaryPayableContract)->pluck('id')->first();
            if(!$SPC){
                return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Salary Payable Contract.');
            }
            else{ $request['SalaryPayableContract']  = $SPC; }
        }
        else{ $request['SalaryPayableContract']  = 0; } 
        

        if($request->EmployeePFContract != null){
            $PFC = DB::table('view_acc_coa')->where('vcode', $request->EmployeePFContract)->pluck('id')->first();
            if(!$PFC){
                return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Employee PF Contract.');
            }
            else{ $request['EmployeePFContract']  = $PFC; }
        }
        else{ $request['EmployeePFContract']  = 0; } 
       
        
        if($request->EmployerPFContract != null){
            $EPFC = DB::table('view_acc_coa')->where('vcode', $request->EmployerPFContract)->pluck('id')->first();
            if(!$EPFC){
             return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Employer PF Contract.');
            }
            else{ $request['EmployerPFContract']  = $EPFC; }
        }
        else{ $request['EmployerPFContract']  = 0; }
        
        
        if($request->EmployeeEOBIContract != null){
            $EobiC = DB::table('view_acc_coa')->where('vcode', $request->EmployeeEOBIContract)->pluck('id')->first();
            if(!$EobiC){
             return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Employee EOBI Contract.');
            }
            else{ $request['EmployeeEOBIContract']  = $EobiC; }
        }
        else{ $request['EmployeeEOBIContract']  = 0; }
        
       
        if($request->EmployerEOBIContract != null){
            $EobirC = DB::table('view_acc_coa')->where('vcode', $request->EmployerEOBIContract)->pluck('id')->first();
            if(!$EobirC){
                return redirect()->back()->withInput($request->input())->with('error', 'Invalid Code Employer EOBI Contract.');
            }
            else{ $request['EmployerEOBIContract']  = $EobirC; }
        }
        else{ $request['EmployerEOBIContract']  = 0; }
           

        if($request->id){

            $id=$request->id;
            return $this->attDeptGroupModel->updateDeptGroup($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
            
        } else {
        
            return $this->attDeptGroupModel->createDeptGroup($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){

        return $this->attDeptGroupModel->deleteDeptGroup($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }
}
