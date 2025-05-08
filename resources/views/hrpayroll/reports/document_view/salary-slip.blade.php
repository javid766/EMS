<html>
<head>
    <title>Document View</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style type="text/css">
        * {
            border-color: black !important;
        }
        .border-all-1 {
            border: 1px solid black !important;
        }
        .border-all-2 {
            border: 2px solid black;
        }
        .align-center {
            text-align: center;
        }
        .align-right {
            text-align: right;
        }
        .valign-top {
            vertical-align: top !important;
        }
        .ptb-10 {
            padding: 10px 0;
        }
        .bdr-r-1 {
            border-right: 1px solid black;
        }
        .fw-normal {
            font-weight: normal;
        }
        
        .ss-label {
            background-color: #ccc;
            border-radius: 5px;
            border: 1px solid black;
        }
        .ss-td-label {
            background-color: #ccc;
            margin: 2px 0;
        }
        
        body table {
            vertical-align: middle;
            margin-bottom: 0px !important;
        }
        th, .body-top-tbl td, .emp-info-tbl td {
            border: none !important;
            vertical-align: middle !important;
        }
        .body-top-tbl {
            font-size: 13px !important;
        }
        .body-top-tbl td {
            padding: 2px 8px !important;
        }
        .emp-info-tbl, .emp-tbl-header {
            border-top: none !important;
            margin: 0 auto 10px;
        }
        .emp-tbl-header th {
            vertical-align: middle !important;
        }
        .emp-salary-tbl {
            margin: 0 auto !important;
        }
        .emp-salary-tbl-col {
            margin: auto !important;
        }
        .emp-salary-tbl-col tr td {
            border-bottom: 1px solid black !important;
        }
        .emp-salary-tbl-col tr td:first-child {
            /*border-right: 1px solid black !important;*/
        }
        .emp-salary-tbl-col tr:not(:last-child) {
            border-bottom: 1px solid black;
        }
        .emp-salary-tbl-col .value {
            padding-right: 10px !important;
        }
        .emp-salary-tbl-col .label {
            border-right: 1px solid black;
            border-color: white !important;
            padding: 5px 10px;
        }
        .emp-salary-tbl-col .label.total {
            font-weight: bold;
        }
        .body-footer-tbl, .emp-att-detail-tbl {
            margin: 2px 7px !important;
        }
        .note {
            font-size: 13px;
            padding-left: 10px;
        }
        .net-salary-label {
            padding-right: 10px;
            font-weight: bold;
            font-size: 15px;
            text-align: right;
        }
        .net-salary-value {
            padding-right: 10px;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>
<body>
    <br><br><br>
    <div class="salary-slip-main border-all-2" style="width: 100%;">
        <table class="table emp-tbl-header">
            <thead>
                <tr>
                    <th width="37%">
                        @if(file_exists(public_path('images/company/'.($companycode??'-').'/logo.png')))
                            <img src="{{asset('images/company/'.($companycode??'-').'/logo.png')}}" height="50px"/>
                        @else
                            <h4>Company Logo</h4>
                        @endif
                    </th>
                    <th width="26%" class="align-center"><h5 class="ss-label ptb-10">Salary Slip</h5></th>
                    <th width="37%" class="align-right"><span class="fw-normal">For the Month:</span> {{ isset($vdate) ? date('M-Y', strtotime($vdate)) : '-' }}</th>
                </tr>
            </thead>
        </table>
        <table class="emp-info-tbl valign-top" width="98.5%">
            <tbody>
                <tr class="valign-top">
                    <td width="50%">
                        <table class="table body-top-tbl">
                            <tbody>
                                <tr>
                                    <td class="ss-td-label" width="80px">Emp Code</td>
                                    <td>{{ $empcode??'-' }}</td>
                                </tr>
                                <tr>
                                    <td class="ss-td-label" width="80px">Name</td>
                                    <td>{{ $employeename??'-' }}</td>
                                </tr>
                                <tr>
                                    <td class="ss-td-label" width="80px">Designation</td>
                                    <td>{{ $designationname??'-' }}</td>
                                </tr>
                                <tr>
                                    <td class="ss-td-label" width="80px">Department</td>
                                    <td>{{ $departmentname??'-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="35%">
                        <table class="table body-top-tbl" height="155px">
                            <tbody>
                                <tr>
                                    <td class="ss-td-label" width="80px">Emp Status</td>
                                    <td>{{ $probitionstatus??'-' }}</td>
                                </tr>
                                <tr>
                                    <td class="ss-td-label" width="80px">Joining Date</td>
                                    <td>{{ isset($doj) ? date('d-M-Y', strtotime($doj)) : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="ss-td-label" width="80px">Salary Days</td>
                                    <td>{{ number_format($salarydays??0, 0) }}</td>
                                </tr>
                                <tr>
                                    <td class="ss-td-label" width="80px">-</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td>
                        @if (($ispaid??0) == 1)
                            <img src="{{asset('images/paid.jpg')}}" height="70px"/>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="emp-salary-tbl" width="97.7%">
            <thead>
                <tr>
                    <td class="ss-td-label border-all-1 align-center" width="49.8%">Allowances</td>
                    <td></td>
                    <td class="ss-td-label border-all-1 align-center" width="49.8%">Deductions</td>
                </tr>
                <tr>
                    <td class="border-all-1 align-center" width="49.8%">
                        <table class="emp-salary-tbl-col" width="100%">
                            <tbody>
                                <tr>
                                    <td class="label" width="50%">Salary</td>
                                    <td class="value align-right" width="50%">{{ number_format($calculatedsalary??0) }}</td>
                                </tr>
                                <tr>
                                    <td class="label" width="50%">Allowance</td>
                                    <td class="value align-right" width="50%">{{ number_format($allowance??0) }}</td>
                                </tr>
                                <tr>
                                    <td class="label" width="50%">Overtime</td>
                                    <td class="value align-right" width="50%">{{ number_format($overtime??0) }}</td>
                                </tr>
                                @php $totalAllowance = (($calculatedsalary??0) + ($allowance??0) + ($overtime??0)); @endphp
                                <tr>
                                    <td class="label total" width="50%">Total Allowances</td>
                                    <td class="value align-right" width="50%">{{ number_format($totalAllowance) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td></td>
                    <td class="border-all-1 align-center" width="49.8%">
                        <table class="emp-salary-tbl-col" width="100%">
                            <tbody>
                                <tr>
                                    <td class="label" width="50%">Income Tax</td>
                                    <td class="value align-right" width="50%">{{ number_format($incometax??0) }}</td>
                                </tr>
                                <tr>
                                    <td class="label" width="50%">Laon</td>
                                    <td class="value align-right" width="50%">{{ number_format($loan??0) }}</td>
                                </tr>
                                <tr>
                                    <td class="label" width="50%">Advance</td>
                                    <td class="value align-right" width="50%">{{ number_format($advance??0) }}</td>
                                </tr>
                                @php $totalDeduction = ($incometax??0) + ($loan??0) + ($advance??0); @endphp
                                <tr>
                                    <td class="label total" width="50%">Total Deduction</td>
                                    <td class="value align-right" width="50%">{{ number_format($totalDeduction) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </thead>
        </table>
        <table class="border-all-1 body-footer-tbl" width="100%">
            <tbody>
                <tr>
                    <td class="note" width="50%">Note:This is a system generated salary slip</td>
                    <td class="bdr-r-1 net-salary-label" width="25.3%">Net Salary</td>
                    <td class="net-salary-value" width="24.7%">{{ number_format($netpayable??0) }}</td>
                </tr>
            </tbody>
        </table>
        <table class="border-all-1 emp-att-detail-tbl" width="100%">
            <tbody>
                <tr>
                    <td class="ss-td-label border-all-1 align-center">Month Days</td>
                    <td class="ss-td-label border-all-1 align-center">Working Days</td>
                    <td class="ss-td-label border-all-1 align-center">Deduction Days</td>
                    <td class="ss-td-label border-all-1 align-center">Salary Days</td>
                </tr>
                <tr>
                    <td class="border-all-1 align-center">{{ number_format($monthdays??0, 0) }}</td>
                    <td class="border-all-1 align-center">{{ number_format($workingdays??0, 0) }}</td>
                    <td class="border-all-1 align-center">{{ number_format($deductiondays??0, 0) }}</td>
                    <td class="border-all-1 align-center">{{ number_format($salarydays??0, 0) }}</td>
                </tr>
                <tr>
                    <td class="ss-td-label border-all-1 align-center">Present Days</td>
                    <td class="ss-td-label border-all-1 align-center">Absent Days</td>
                    <td class="ss-td-label border-all-1 align-center">Off Days</td>
                    <td class="ss-td-label border-all-1 align-center">Leaves Days</td>
                </tr>
                @php
                    $absentDays = ($abdays??0) + ($wodays??0);
                    $offDays = ($wedays??0) + ($ghdays??0) + ($fhdays??0);
                    $leaves = ($cldays??0) + ($sldays??0) + ($aldays??0) + ($cpldays??0) + ($spldays??0) + ($mldays??0) + ($shortldays??0) + ($hdldays??0);
                @endphp
                <tr>
                    <td class="border-all-1 align-center">{{ number_format($physicaldays??0, 0) }}</td>
                    <td class="border-all-1 align-center">{{ number_format($absentDays??0, 0) }}</td>
                    <td class="border-all-1 align-center">{{ number_format($offDays??0, 0) }}</td>
                    <td class="border-all-1 align-center">{{ number_format($leaves??0, 0) }}</td>
                </tr>
            </tbody>
        </table>
        <br>
    </div>
</body>
</html>
