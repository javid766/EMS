<?php

namespace App\Http\Controllers\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Employee\SalLoanDeduct;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class LoanDeductionController extends Controller
{
    public $DeptModel;
    public $salLoanDeductModel;
    public $utilsModel;

    public function __construct() {
        $this->salLoanDeductModel = new SalLoanDeduct();
        $this->DeptModel = new Dept();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){

        $allDepts = $this->getDepartments($request);
        return view('hrpayroll.employee.loan_deduction',compact('allDepts'));
    }

    public function search(Request $request){
        $deptid = (int)$request->get('deptid');
        $datein  = $request->get('vdatein');
        $curdate = date("M Y",strtotime($datein)); 

        //first date of the month
        $first_date_find = strtotime(date("Y-m-d", strtotime($datein)) . ", first day of this month");
        $first_date = date("Y-m-d",$first_date_find);

        //last date of the month
        $last_date_find = strtotime(date("Y-m-d", strtotime($datein)) . ", last day of this month");
        $last_date = date("Y-m-d",$last_date_find);

        $request->request->add([
            'employeeid' => 0,
            'datein'   =>  $first_date,
            'dateout'   => $last_date,
        ]);

        $data = $this->getData($request);
        $data = $data->sort();
        foreach ($data as $key => $value) {
            $value->amount = floatval($value->amount);
            $value->amount = round($value->amount, 2);
            if ($deptid) {
                if($value->deptid != $deptid){ 
                    unset($data[$key]);
                }
                $value['datein'] = $datein;
                $value['filldate'] = $curdate;
                $value['balance'] = $value['loanamount'] - $value['loanusedamount'];
                $loandate = date("d/m/Y",strtotime($value->loandate)); 
                $value['loandate'] = $loandate;
            } 
            else{
                $value['datein'] = $datein;
                $value['filldate'] = $curdate;
                $value['balance'] = $value['loanamount'] - $value['loanusedamount'];
                $loandate = date("d/m/Y",strtotime($value->loandate)); 
                $value['loandate'] = $loandate;

            }
        }
        return redirect()->back()->withInput($request->input())->with(['data' => $data]);
    }

    public function getData($request, $id=0){

        return $this->salLoanDeductModel->getSalLoanDeducts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function save(Request $request){
        $griddata = $request->all();   
        $result = '';
        if ($request->get('data') == null) {
            return redirect()->back()->withInput($request->input())->with('success' , "Nothing to change.");
        }
        else{
            try{
                foreach ($griddata['data'] as  $value) {
                        $id = $value['id'];
                        if (array_key_exists("amount",$value)){
                            if($value['amount'] != "0.0000"){
                               $_request = $request;
                               $_request->request->add($value);
                               if($id <=0){
                                 $result =  $this->salLoanDeductModel->createSalLoanDeduct($request, $this->utilsModel->CALL_TYPE_DEFAULT);
                             }
                             elseif ($id >0) {
                                 $result =   $this->salLoanDeductModel->updateSalLoanDeduct($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
                            }

                        }
                    }
                 }
            }
            catch(\Illuminate\Database\QueryException $ex){
                if (($ex->getMessage())) {
                    return redirect()->back()->withInput($request->input())->with('error' , $ex->getMessage()); 
                }
            }
        if ($result) {
            return $result;
        }
        else{
            return redirect()->back()->withInput($request->input())->with('success' , 'Nothing to change.');   
        }                       
    }

}

    public function delete($id){

        return $this->salLoanDeductModel->deleteSalLoanDeduct($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function getDepartments(Request $request, $id=0){

    $deptsData = $this->DeptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($deptsData); 
       return $deptsData;
    }

}
