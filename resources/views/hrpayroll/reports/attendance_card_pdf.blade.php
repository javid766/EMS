<! DOCTYPE html>
   <html>
   <head>
      <title>Attendance Card</title>
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

        .box {
           height: 20px;
           width: 20px;
           margin-bottom: 15px;
           border: 1px solid black;
           vertical-align: middle;
         }

         .red {
           background-color: red;
         }

         .green {
           background-color: green;
         }

         .blue {
           background-color: blue;
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

             <div>Employee Monthly Attendance Card For the: <span class="sdate">{{$datefrom ?? '-'}} To {{$dateto ?? '-'}}</span></div>
         </div>
         <?php 

            if (sizeof($attData) != 0) {

               foreach ($attData as $key1 => $value1) { 
                  

                  ?> 
               <div class="sslip-empdetail">
                     <div class="emp-tbl1">
                           <table id="monthlySaltable" class="table table-hover">
                              <thead>
                                 <tr style="background-color: #07ffd138;"> 
                                    <th colspan="8" style="font-size: 14px;">{{ $key1 }}</th>  
                                 </tr>
                                 <tr> 
                                    <th>Sr#</th>  
                                    <th>Day</th>  
                                    <th>Cloasing Date</th>  
                                    <th>Time in</th>
                                    <th>Timeout</th>
                                    <th>Tot Time</th>
                                    <th>Status</th>  
                                    <th>Attendance</th>
                                 </tr>

                                 <?php foreach ($value1 as $key2 => $value2) {

                                  ?> 
                                    <tr>
                                       <td>{{ $value2->empcode ?? '-'}}</td>
                                       <td>{{ $value2->employeename ?? '-'}}</td>
                                       <td>{{ $value2->vdate ?? '-'}}</td>
                                       <td>{{ $value2->starttime ?? '-'}}</td>
                                       <td>{{ $value2->tottime ?? '-'}}</td>
                                       <td>{{ $value2->overtime ?? '-'}}</td>
                                       <td style="padding-top: 3px;"><div class='box red'></div></td>
                                       <td >{{ $value2->attcode ?? '-'}}</td>
                                    </tr>  
                                 <?php } ?>

                            
                              </thead>
                           <tbody>
                        </tbody>
                     </table>
                  </div> 
               </div>
            <?php }}?>    
         </div> 
   </body>
</html>

