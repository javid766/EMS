<! DOCTYPE html>
   <html>
   <head>
      <title>Salary Slip</title>
      <link rel="stylesheet" href="{{ asset('dist/css/theme.css') }}">
      <link rel="stylesheet" href="{{ asset('all.css') }}">
      <style type="text/css">
         * {
           box-sizing: border-box;
           font-size: 11px;
         }
         .sslipmain{
            border: 1px solid #000;
         }
         .ssliphead{
            border-bottom: 1px solid #000;
            width: 100%;
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
         .emp-tbl1{
            width: 35.5%;
            float: left;
            padding: 5px;
            margin-right: 5px;
            margin-left: 5px;
            border: 1px solid black;
         }
         .emp-tbl1 table{
            width: 100%;
         }
         .emp-tbl1 td{
            font-size: 10px !important;
            width: 50%;
            margin-left: 5% !important;
            padding-left: 5px;
            padding-bottom: 2px;
         }
         .emp-tbl1 .tdbg-colr{
            width: 25%;
            margin-bottom: 5px !important;
            font-weight: 700;
            margin-left: 0px !important;
            text-align: left;
         }
         .emp-tbl2 .tdbg-colr{
            width: 30%;
            margin-bottom: 5px !important;
            padding-left: 5px;
            padding-bottom: 2px;
            font-weight: 700;
         }
         .emp-tbl2{
            width: 25%;
            float: left;
            padding: 5px;
            margin-right: 5px;
            border: 1px solid black;
            font-size: 10px !important;
         }
         .emp-tbl2 table{
            width: 100%;
         }
         .emp-tbl2 td{
            width: 50%;
            padding-left: 5px;
            padding-bottom: 2px;
            margin-left: 5% !important;
         }
         .emp-tbl2 .tdbg-colr{
            width: 30%;
            margin-bottom: 5px !important;
            margin-left: 0px !important;
            padding-left: 5px;
            padding-bottom: 2px;
            font-weight: 700;
         }
         .emp-tbl3{
            width: 31.5%;
            float: left;
            padding: 5px;
            margin-right: 5px;
            border: 1px solid black;
            font-size: 10px !important;
         }
         .emp-tbl3 table{
            width: 100%;
         }
         .emp-tbl3 td{
            width: 50%;
            padding-left: 5px;
            padding-bottom: 2px;
            margin-left: 5% !important;
         }
         .emp-tbl3 .tdbg-colr{
            width: 30%;
            margin-bottom: 5px !important;
            margin-left: 0px !important;
            padding-left: 5px;
            padding-bottom: 2px;
            font-weight: 700;
         }
         .sslip-footer{
            width: 100%;
            margin: 5px;
            padding: 5px;
         }
         .netsal{
            font-weight: 700;
            text-align: left;
         }
         .allowanceDeductiontbl{
            width: 100%;
            margin-top: 10px;
            margin-left: 5px;
            margin-right: 5px;
         }
         .allowncetbl{
            font-size: 11px;
         }
         .allowncetbl table{
            width: 100%;
         }
         .allowncetbl td{
            border: 1px solid black;
            padding: 5px;
         }
         .allowncetbl th{
            border: 1px solid black;
            padding: 5px;
            background-color: #d9d9d9;
         }
         .tdbg-colr{
            background-color: #d9d9d9;
         }
         .sdate{
            font-weight: 700;
            margin-left: 5px;
         }
      </style>
   </head>
   <body>
      <?php 
      $url = url('/');
      $src = '/img/EMS-zeta.png';

      if (sizeof($salSlipData) != 0) {
      
         foreach ($salSlipData as $key => $value) {
      
            $empcode = $value->empcode;
            $employeename = $value->employeename;
            $designation = $value->designation;
            $department = $value->department;
            $locationname = $value->locationname;
            $doj = $value->doj;
            $cnicno = $value->cnicno;
            $doj = date("d-M-Y", strtotime($doj));
            $salarydays = $value->salarydays;
            if ($salarydays == 0 || $salarydays == '') {
      
               $salarydays = '-';
            }
            else{
               $salarydays = round($salarydays);
            }
            $incometax = $value->incometax;
            if ($incometax == 0 || $incometax == '') {
               $incometax = '-';
            }
            else{
               $incometax = 'PKR. '.$incometax;
            }
            $paymentmode = $value->paymentmode;
            $comapnybankname = $value->comapnybankname;
            $comapnybranchname = $value->comapnybranchname;
            $bankaccount = $value->bankaccount;
            if ($bankaccount == 0 || $bankaccount == '') {
               $bankaccount = '-';
            }
            else{
               $bankaccount = $bankaccount;
            }
            $salary = $value->salary;
            if ($salary == 0 || $salary == '') {
               $salary = '-';
            }
            else{
               $salary = 'PKR. '.$salary;
            }
            $advance = $value->advance;
            if ($advance == 0 || $advance == '') {
               $advance = '-';
            }
            else{
               $advance = 'PKR. '.$advance;
            }
            $loan = $value->loan;
            if ($loan == 0 || $loan == '') {
               $loan = '-';
            }
            else{
               $loan = 'PKR. '.$loan;
            }
            $netpayable = $value->netpayable;
            if ($netpayable == 0 || $netpayable == '') {
               $netpayable = '-';
            }
            else{
               $netpayable = 'PKR. '.$netpayable;
            }

            $grade = $value['grade'];
            $empStatus = $value['empstatus'];
            $ytdIncome = $value['ytdIncome'];
            $ytdTax = $value['ytdTax'];
            $ntnNo = $value['ntnNo'];
         }
      }
      ?>
      <div class="sslipmain">
         <div class="ssliphead">
            <div class="logo"><img src="{{ $url.$src }}"></div>
            <div class="sslipdesc">
               <span>Salary Slip</span>
            </div>
            <div class="sslipdate">For the Month: <span class="sdate">{{$salvdate ?? '-'}}</span></div>
         </div>
         <div class="sslip-empdetail">
            <div class="emp-tbl1">
               <table class="">
                  <tr>
                     <td class="tdbg-colr">Emp. Code</td>
                     <td>{{ $empcode ?? '-' }}</td>
                  </tr>
                  <tr>
                     <td class="tdbg-colr">Name</td>
                     <td>{{ $employeename ?? '-' }}</td>
                  </tr>
                  <tr>
                     <td class="tdbg-colr">Designation</td>
                     <td>{{ $designation ?? '-' }}</td>
                  </tr>
                  <tr>
                     <td class="tdbg-colr">Grade</td>
                     <td>{{ $grade ?? '-' }}</td>
                  </tr>
                  <tr>
                     <td class="tdbg-colr">Department</td>
                     <td>{{ $department ?? '-' }}</td>
                  </tr>
                  <tr>
                     <td class="tdbg-colr" style="margin-bottom: 0px !important;">Location</td>
                     <td>{{ $locationname ?? '-' }}</td>
                  </tr>
               </table>
            </div>
            <div class="emp-tbl2">
               <table class="">
                  <tr>
                     <td class="tdbg-colr">Emp. Status</td>
                     <td>{{ $empStatus ?? '-' }}</td>
                  </tr>
                  <tr>
                     <td class="tdbg-colr">Joining Date</td>
                     <td>{{ $doj ?? '-'}}</td>
                  </tr>
                  <tr>
                     <td class="tdbg-colr">Salary Days</td>
                     <td>{{ $salarydays ?? '-' }}</td>
                  </tr>
                  <tr>
                     <td class="tdbg-colr">Tax</td>
                     <td>{{ $incometax ?? '-' }}</td>
                  </tr>
                  <tr>
                     <td class="tdbg-colr">YTD Income</td>
                     <td>{{ $ytdIncome ?? '-' }}</td>
                  </tr>
                  <tr>
                     <td class="tdbg-colr" style="margin-bottom: 0px !important;">YTD Tax</td>
                     <td>{{ $ytdTax ?? '-' }}</td>
                  </tr>
               </table>
            </div>
            <div class="emp-tbl3">
               <table class="">
                  <tr>
                     <td class="tdbg-colr">Payment Mode</td>
                     <td>{{ $paymentmode ?? '-' }}</td>
                  </tr>
                  <tr>
                     <td class="tdbg-colr">Bank Name</td>
                     <td>{{ $comapnybankname ?? '-' }}</td>
                  </tr>
                  <tr>
                     <td class="tdbg-colr">Branch</td>
                     <td>{{ $comapnybranchname ?? '-' }}</td>
                  </tr>
                  <tr>
                     <td class="tdbg-colr">Account No.</td>
                     <td>{{ $bankaccount ?? '-' }}</td>
                  </tr>
                  <tr>
                     <td class="tdbg-colr">NTN No.</td>
                     <td>{{ $ntnNo ?? '-' }}</td>
                  </tr>
                  <tr>
                     <td class="tdbg-colr" style="margin-bottom: 0px !important;">CNIC No.</td>
                     <td>{{ $cnicno ?? '-' }}</td>
                  </tr>
               </table>
            </div>
         </div>
         <div class="allowanceDeductiontbl">
            <div class="allowncetbl">
               <table class="" width="100%">
                  <thead>
                     <tr>
                        <th>Salary</th>
                        <th>Income Tax</th>
                        <th>Advance</th>
                        <th>Loan</th>
                        <th>Net Salary</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>{{ $salary ?? '-' }}</td>
                        <td>{{ $incometax ?? '-' }}</td>
                        <td>{{ $advance ?? '-' }}</td>
                        <td>{{ $loan ?? '-' }}</td>
                        <td>{{ $netpayable ?? '-' }}</td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
         <div class="sslip-footer">
            <table width="100%">
               <tr>
                  <td class="netsalnote">Note:This is a system generated salary slip and doesn't require any</td>
               </tr>
            </table>
         </div>   
      </div> 
   </body>
   </html>

