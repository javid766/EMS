<! DOCTYPE html>
   <html>
   <head>
      <title>Salary Sheet</title>
      <link rel="stylesheet" href="{{ asset('dist/css/theme.css') }}">
      <link rel="stylesheet" href="{{ asset('all.css') }}">
             <style type="text/css">
         * {
           box-sizing: border-box;

           font-size: 11px;
         }
         .sslip-empdetail table {
             border-collapse:separate;
             border-spacing:2px 0;
         }
        .sslip-empdetail td {
            border-bottom: #000 dotted 1px;
            padding: 2px 5px !important;
         }
        

         .sslipmain{
            margin-bottom: 20px;
            width: 1000px;
            margin: 0 auto;
         }
         .ssliphead{
            border-bottom: 1px solid #000;
            width: 80%;
         }
         .logo{
            display: inline-block;
            width: 40%;
         }
         .logo img{
            width: 85%;
            height: 45px;
            margin-left: 15px;
            margin-top: 20px;
         }
         .sslipdesc{
            background-color: #d9d9d9;
            display: inline-block;
            font-weight: 800;
            font-size: 16px !important;
            text-align: center;
            width: 15%;
            padding-bottom: 5px;
            padding-left: 20px;
            padding-right: 20px;
            margin-left: 4%;
         }
         .sslipdate{
            display: inline-block;
            width: 20%;
            text-align: right;
            font-weight: 11px;
         }
         .sslip-empdetail{
            margin-top: 20px;
         }
         .sslip-empdetail::after{
            content: "";
            clear: both;
         }
         
         .sslip-footer{
            width: 100%;
            margin: 5px;
            padding: 5px;
            margin-top: 100px;
         }
         #monthlySaltable th{

            font-size: 10px;
            border-top: 1px solid;
            border-bottom: 1px solid;
            padding-top: 0px;
         }
         #monthlySaltable td{

            font-size:8px;
         }
         #monthlySaltable{
            border-collapse: collapse;
         }
         
         .department {
                background-color: #ffc10738;
                margin-bottom: 10px;
                border-bottom: 1px solid;
         }
         .department_total {

         }
         .grand_total_table {
            width: 100%;
            margin-top: 30px;

         }
        .grand_total_table th {
             border: 1px solid;
            border-collapse: collapse;
            padding: 10px;
        }
        .netsalnote.right{
         text-align: right !important;
        }
        .grad_total_class{
            width: 36%;
        }
        
      </style>
   </head>
   <body>
      <?php 
      $url = url('/');
      $src = '/img/EMS-zeta.png';
      ?>
      <div class="sslipmain">
         <div class="ssliphead">
            <div class="logo"><img src="{{ $url.$src }}"></div>
            <div>Employee Salary Sheet For the Month: <span class="sdate">{{$salvdate ?? '-'}}</span></div>
         </div>
         <?php 

            $grand_total_salary = 0;
            $grand_total_overtimers = 0;
            $grand_total_performance_allowance = 0;
            $grand_total_special_allowance = 0;
            $grand_total_eid_bonus = 0;
            $grand_total_arears = 0;
            $grand_total_gross_salary = 0;
            $grand_total_income_tax = 0;
            $grand_total_eobi_deduction= 0;
            $grand_total_loan_deduction= 0;
            $grand_total_leave_withoutpay = 0;
            $grand_total_other_deduction = 0;
            $grand_total_total_deduction = 0;
            $grand_total_net_salary = 0;


            if (sizeof($salSheetData) != 0) {

               foreach ($salSheetData as $key1 => $value1) { 
                     $department_total_salary = 0;
                     $department_total_overtimers = 0;
                     $department_total_performance_allowance = 0;
                     $department_total_special_allowance = 0;
                     $department_total_eid_bonus = 0;
                     $department_total_arears = 0;
                     $department_total_gross_salary = 0;
                     $department_total_income_tax = 0;
                     $department_total_eobi_deduction= 0;
                     $department_total_loan_deduction= 0;
                     $department_total_leave_withoutpay = 0;
                     $department_total_other_deduction = 0;
                     $department_total_total_deduction = 0;
                     $department_total_net_salary = 0;

                  ?> 
               <div class="sslip-empdetail">
                     <div class="department">{{ $key1 }}</div>
                     <div class="emp-tbl1">
                           <table id="monthlySaltable" class="table table-hover">
                              <thead>
                                 <tr> 
                                    <th>E-Code</th>  
                                    <th>Employee</th>  
                                    <th>Department</th>  
                                    <th>Designation</th>
                                    <th>Doj</th>
                                    <th>Basic Salary</th>
                                    <th>Days</th>  
                                    <th>Salary</th>
                                    <th>Overtimer</th>
                                    <th>Perform. Allowanc</th> 
                                    <th>Special Allowanc</th>  
                                    <th>Eid Bonus</th>                     
                                    <th>Arrears</th>
                                    <th>Gross Salary</th>  
                                    <th>Income Tax</th>  
                                    <th>EOBI Deduc</th> 
                                    <th>Laon Deduc</th> 
                                    <th>Leave Without Pay</th> 
                                    <th>Other Deduc.</th> 
                                    <th>Total Deduc</th>  
                                    <th>Net Salary</th>  
                                    <th>P-Mode</th>  
                                 </tr>

                                 <?php foreach ($value1 as $key2 => $value2) {

                                    // Get department wise total
                                    $department_total_salary += $value2->calculatedsalary;
                                    $department_total_overtimers += $value2->overtimers;
                                    $department_total_performance_allowance += $value2->performanceallowance;
                                    $department_total_special_allowance += $value2->specielallowance;
                                    $department_total_eid_bonus += $value2->eidbonus;
                                    $department_total_arears += $value2->arrears;
                                    $department_total_gross_salary += $value2->grosssalary;
                                    $department_total_income_tax += $value2->incometax;
                                    $department_total_eobi_deduction += $value2->eobideduction;
                                    $department_total_loan_deduction += $value2->loan;
                                    $department_total_leave_withoutpay += $value2->LeaveWithoutPay;
                                    $department_total_other_deduction += $value2->OtherDeduc;
                                    $department_total_total_deduction += $value2->totaldeduction;
                                    $department_total_net_salary += $value2->netpayable;

                                    // Get grand total
                                    $grand_total_salary += $value2->calculatedsalary;
                                    $grand_total_overtimers += $value2->overtimers;
                                    $grand_total_performance_allowance += $value2->performanceallowance;
                                    $grand_total_special_allowance += $value2->specielallowance;
                                    $grand_total_eid_bonus += $value2->eidbonus;
                                    $grand_total_arears += $value2->arrears;
                                    $grand_total_gross_salary += $value2->grosssalary;
                                    $grand_total_income_tax += $value2->incometax;
                                    $grand_total_eobi_deduction += $value2->eobideduction;
                                    $grand_total_loan_deduction += $value2->loan;
                                    $grand_total_leave_withoutpay += $value2->LeaveWithoutPay;
                                    $grand_total_other_deduction += $value2->OtherDeduc;
                                    $grand_total_total_deduction += $value2->totaldeduction;
                                    $grand_total_net_salary += $value2->netpayable;


                                  ?> 
                                    <tr>
                                       <td>{{ $value2->empcode ?? '-'}}</td>
                                       <td>{{ $value2->employeename ?? '-'}}</td>
                                       <td>{{ $value2->department ?? '-'}}</td>
                                       <td>{{ $value2->designation ?? '-'}}</td>
                                       <td>{{ $value2->doj ?? '-'}}</td>
                                       <td>{{ $value2->salary ?? '-'}}</td>
                                       <td>{{ $value2->salarydays ?? '-'}}</td>
                                       <td>{{ $value2->calculatedsalary ?? '-'}}</td>
                                       <td>{{ $value2->overtimers ?? '-'}}</td>
                                       <td>{{ $value2->performanceallowance ?? '-'}}</td>
                                       <td>{{ $value2->specielallowance ?? '-'}}</td>
                                       <td>{{ $value2->eidbonus ?? '-'}}</td>
                                       <td>{{ $value2->arrears ?? '-'}}</td>
                                       <td>{{ $value2->grosssalary ?? '-'}}</td>
                                       <td>{{ $value2->incometax ?? '-'}}</td>
                                       <td>{{ $value2->eobideduction ?? '-'}}</td>
                                       <td>{{ $value2->loan ?? '-'}}</td>
                                       <td>{{ $value2->LeaveWithoutPay ?? '-'}}</td>
                                       <td>{{ $value2->OtherDeduc ?? '-'}}</td>
                                       <td>{{ $value2->totaldeduction ?? '-'}}</td>
                                       <td>{{ $value2->netpayable ?? '-'}}</td>
                                       <td>{{ $value2->pmode ?? '-'}}</td>
                                    </tr>  
                                 <?php } ?>

                                 <tr>
                                    <th></th>
                                    <th></th>
                                    <th colspan="5">Total:</th>
                                    <th>{{ $department_total_salary }}</th>
                                    <th>{{ $department_total_overtimers }}</th>
                                    <th>{{ $department_total_performance_allowance }}</th>
                                    <th>{{ $department_total_special_allowance }}</th>
                                    <th>{{ $department_total_eid_bonus }}</th>
                                    <th>{{ $department_total_arears }}</th>
                                    <th>{{ $department_total_gross_salary }}</th>
                                    <th>{{ $department_total_income_tax }}</th>
                                    <th>{{ $department_total_eobi_deduction }}</th>
                                    <th>{{ $department_total_loan_deduction }}</th>
                                    <th>{{ $department_total_leave_withoutpay }}</th>
                                    <th>{{ $department_total_other_deduction }}</th>
                                    <th>{{ $department_total_total_deduction }}</th>
                                    <th>{{ $department_total_net_salary }}</th>
                                    <th></th>
                                   
                                 </tr>
                              </thead>
                           <tbody>
                        </tbody>
                     </table>
                  </div> 
               </div>
            <?php }}?>

            <div class="grad_total">
               <table class="grand_total_table">
                  <thead>
                     <tr>
                        <th class="grad_total_class">Grand Total:</th>
                        <th>{{ $grand_total_salary }}</th>
                        <th>{{ $grand_total_overtimers }}</th>
                        <th>{{ $grand_total_performance_allowance }}</th>
                        <th>{{ $grand_total_special_allowance }}</th>
                        <th>{{ $grand_total_eid_bonus }}</th>
                        <th>{{ $grand_total_arears }}</th>
                        <th>{{ $grand_total_gross_salary }}</th>
                        <th>{{ $grand_total_income_tax }}</th>
                        <th>{{ $grand_total_eobi_deduction }}</th>
                        <th>{{ $grand_total_loan_deduction }}</th>
                        <th>{{ $grand_total_leave_withoutpay }}</th>
                        <th>{{ $grand_total_other_deduction }}</th>
                        <th>{{ $grand_total_total_deduction }}</th>
                        <th>{{ $grand_total_net_salary }}</th>
           
                     </tr>
                  </thead>
               </table>
            </div> 
            <div class="sslip-footer">
               <table width="100%">
                  <tr>
                     <td class="netsalnote left">Prepared By: ______________________________</td>
                     <td class="netsalnote right">Created By: _______________________________</td>
                  </tr>
               </table>
            </div>    
         </div> 
   </body>
</html>

