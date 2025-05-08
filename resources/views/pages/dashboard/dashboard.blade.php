@extends('layouts.main') 
@section('title', 'Dashboard')
@section('content')
<!-- push external head elements to head -->
@push('head')
<link rel="stylesheet" type="text/css" href="{{ asset('css/dashboard.css') }}">
@endpush
<!-- Main Figures Data -->
<?php 

if (sizeof($attMain) != 0) {

   foreach ($attMain as $val) {
   
     $abratioHeading = $val->r1c1;
     $abratioVal = $val->r1c2;
     $abratioShortDesc = $val->r1c3;

     $ftimeEmpHeading = $val->r2c1;
     $ftimeEmpVal = $val->r2c2;
     $ftimeEmpShortDesc = $val->r2c3;

     $resignNewHiringHeading = $val->r3c1;
     $resignNewHiringVal = $val->r3c2;
     $resignNewHiringShortDesc = $val->r3c3;

     $turnoverHeading = $val->r4c1;
     $turnoverVal = $val->r4c2;
     $turnoverShortDesc = $val->r4c3;
   }
}

?>
<!-- Dashboard Filters -->
<div class="dashboard-filters">
   <form method="post" id="" action="{{ route('applyDateFilter') }}">
      @csrf
      <div class="row">
         <label class="offset-lg-7 offset-md-4 offset-sm-1 col-lg-1 col-md-1 col-sm-1 col-form-label fw-500 pr-0 text-right">Filters: </label>
         <div class="col-lg-3 col-md-6 col-sm-9">
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 pl-0">
                  <input name="vdatefrom" id="vdatefrom" type="date" value="{{ Session::get('vdatefrom') }}" class="form-control input-sm" width="276" required="" />
               </div>
               <div class="col-lg-6 col-md-6 col-sm-6 pl-0">
                  <input name="vdateto" id="vdateto" type="date" value="{{ Session::get('vdateto') }}" class="form-control input-sm" width="276" required=""/>
               </div>
            </div>
         </div>
         <div class="col-lg-1 col-md-1 col-sm-1 pl-0">
            <button type="submit" name="apply_datefilter" class="btn btn-success apply-btn" id="apply_datefilter">Apply</button> 
         </div>
      </div>
   </form>
</div>
<!-- att main start -->
<div class="dashboard-content">
   <div class="row attmain">
      <div class="col-xl-3 col-md-6">
         <div class="card prod-p-card">
            <div class="card-body card-new-red h-65">
               <div class="row align-items-center">
                  <div class="col-auto heading-border-right">
                     <i class="fa fa-calendar text-new-red f-18"></i>
                  </div>
                  <div class="col">
                     <h6 class="mb-5 text-white">{{ $abratioHeading ?? '' }}</h6>
                  </div>
               </div>
            </div>
            <div class="card-body h-60">
               <table class="table boxes">
                  <tr>
                     <td><span class="boxsdesc">{{ $abratioShortDesc ?? '' }}</span></td>
                     <td><span class="boxvalue">{{ $abratioVal ?? '' }}</span></td>
                  </tr>
               </table>
            </div>
         </div>
      </div>
      <div class="col-xl-3 col-md-6">
         <div class="card prod-p-card">
            <div class="card-body card-new-blue h-65">
               <div class="row align-items-center">
                  <div class="col-auto heading-border-right">
                     <i class="fa fa-users text-new-blue f-18"></i>
                  </div>
                  <div class="col">
                     <h6 class="mb-5 text-white">{{ $ftimeEmpHeading ?? '' }}</h6>
                  </div>
               </div>
            </div>
            <div class="card-body h-60">
               <table class="table boxes">
                  <tr>
                     <td><span class="boxsdesc">{{ $ftimeEmpShortDesc ?? '' }}</span></td>
                     <td><span class="boxvalue">{{ $ftimeEmpVal ?? '' }}</span></td>
                  </tr>
               </table>
            </div>
         </div>
      </div>
      <div class="col-xl-3 col-md-6">
         <div class="card prod-p-card">
            <div class="card-body card-new-yellow h-65">
               <div class="row align-items-center">
                  <div class="col-auto heading-border-right">
                     <i class="fa fa-users text-new-yellow f-18"></i>
                  </div>
                  <div class="col">
                     <h6 class="mb-5 text-white">{{ $resignNewHiringHeading ?? '' }}</h6>
                  </div>
               </div>
            </div>
            <div class="card-body h-60">
               <table class="table boxes">
                  <tr>
                     <td><span class="boxsdesc">{{ $resignNewHiringShortDesc ?? '' }}</span></td>
                     <td><span class="boxvalue">{{ $resignNewHiringVal ?? '' }}</span></td>
                  </tr>
               </table>
            </div>
         </div>
      </div>
      <div class="col-xl-3 col-md-6">
         <div class="card prod-p-card">
            <div class="card-body card-new-green h-65">
               <div class="row align-items-center">
                  <div class="col-auto heading-border-right">
                     <i class="fa fa-chart-line text-new-green f-18"></i>
                  </div>
                  <div class="col">
                     <h6 class="mb-5 text-white">{{ $turnoverHeading ?? '' }}</h6>
                  </div>
               </div>
            </div>
            <div class="card-body h-60">
               <table class="table boxes">
                  <tr>
                     <td><span class="boxsdesc">{{ $turnoverShortDesc ?? '' }}</span></td>
                     <td><span class="boxvalue">{{ $turnoverVal ?? '' }}</span></td>
                  </tr>
               </table>
            </div>
         </div>
      </div>
   </div>
   <!-- att main end -->
   <!-- Employee Strength Month Wise -->
   <div class="row mb-15">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <h3 class="pt-15">{{ __('Employee Strength Month Wise')}}</h3>
            </div>
            <div class="card-block">
               <div id="empStrengthMonthWise" class="h250">
                  <div class="graph-preloader h250">&nbsp;</div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Month Wise Salary -->
   <div class="row mb-15">
      <div class="col-sm-6">
         <div class="card">
            <div class="card-header">
               <h3 class="pt-15">{{ __('Month Wise Salary')}}</h3>
            </div>
            <div class="card-block">
               <div id = "monthWiseSalary" class="h250">
                  <div class="graph-preloader h250">&nbsp;</div>
               </div>
            </div>
         </div>
      </div>
      <!-- Department wise Strength -->
      <div class="col-sm-6">
         <div class="card">
            <div class="card-header">
               <h3 class="pt-15">{{ __('Department wise Strength')}}</h3>
            </div>
            <div class="card-block">
               <div id = "deptwisestrength" class="h250">
                  <div class="graph-preloader h250">&nbsp;</div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Current Month Salary -->
   <div class="row mb-15">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header">
               <h3 class="pt-15">{{ __('Department wise Salary of the month')}}</h3>
            </div>
            <div class="card-block">
               <div id="deptwise_currmonthsalary" class="h250">
                  <div class="graph-preloader h250">&nbsp;</div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Department Strength-Month Wise -->
   <div class="row mb-15">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header">
               <h3 class="pt-15">{{ __('Department Strength-Month Wise')}}</h3>
            </div>
            <div class="card-block">
               <div class="table-wrapper-scroll-y custom-scrollbar">
                  <?php
                     if (sizeof($deptWiseGroup) != 0) {?>
                  <table class="table table-striped table-bordered" id="deptWiseStrengthmonth">
                     <thead>
                        <tr>
                           <th></th>
                           <th>Jan</th>
                           <th>Feb</th>
                           <th>March</th>
                           <th>April</th>
                           <th>May</th>
                           <th>June</th>
                           <th>July</th>
                           <th>Aug</th>
                           <th>Sept</th>
                           <th>Oct</th>
                           <th>Nov</th>
                           <th>Dec</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($deptWiseGroup as $deptname => $deptGroup)
                        <tr>
                           @php
                           $i = 0
                           @endphp
                           <td>{{ $deptname ?? '' }}</td>
                           @foreach ($deptGroup as $value)
                           @php
                           $i++
                           @endphp
                           <td>{{ $value['strength'] ?? '' }}</td>
                           @endforeach
                           <?php   
                              $count  = 12-$i;
                              for ($i=0; $i < $count ; $i++) { ?>
                           <td> - </td>
                           <?php }
                              ?>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                  <?php } 
                     else{?>
                  <div class='text-center'><span class='text-red'> No Data</span></div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- Attendance Activity -->
   <div class="row mb-15">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <h3 class="pt-15">{{ __('Attendance Activity')}}</h3>
            </div>
            <div class="card-block">
               <div id="att_activity" class="h330">
                  <div class="graph-preloader h330">&nbsp;</div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row mb-15">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <h3 class="pt-15">{{ __('Department wise Strength-Current Month')}}</h3>
            </div>
            <div class="card-block">
               <div id="deptWiseStrengthTree" class="h260">
                  <div class="graph-preloader h260">&nbsp;</div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- push external js -->
@push('script')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(empStrengthMonthWise);

  function empStrengthMonthWise() {

    var data = google.visualization.arrayToDataTable([
      ['Date', 'Strength', 'Newcommers', 'LeftEmployees'],
      @php

      foreach($empStrengthMonthWise as $val) {
          echo "['".$val->vdate."', ".$val->strength.",".$val->newcommers.", ".$val->leftemp."],";
      }
      @endphp
      ]);

    var options = {
      seriesType: 'bars',
      bar: {groupWidth: "30%"},
      legend: { position: "bottom" },
      'height':250,
      pointsVisible: true,
      colors: ['#33414e','#ff4e43','#ecae36'],
      series: {
        1: {type: 'line'},
        2: {type: 'line'},
    },
    chartArea: {left:50,top:30,right:10}
};
if(data.getNumberOfRows() == 0){
    $('.graph-preloader').css('background', 'none');
    $("#empStrengthMonthWise").prepend("<div class='no-data text-center'><span class='text-red'> No Data</span></div>");
}
else{
           // Instantiate and draw the chart.
           var chart = new google.visualization.ComboChart(document.getElementById('empStrengthMonthWise'));
           chart.draw(data, options); 
       }

   }
</script>
<script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(MonthWiseSalary);
    function MonthWiseSalary() {
      var data = google.visualization.arrayToDataTable([
       ["Department", "Salary" ],
       @php
       foreach($deptmonthWiseSalary as $data) {
          echo "['".$data->departmentname."', ".$data->salary."],";
      }
      @endphp
      ]);
      var view = new google.visualization.DataView(data);
      var options = {
        vAxis: {format: 'short'},
        legend: { position: "none" },
        hAxis : { 
            textStyle : {
              fontSize: 9 // or the number you want
          }
      },
      'height':250,
      chartArea: {left:50,top:30,right:10},
      colors:['#33414e']
  };
  if(data.getNumberOfRows() == 0){
    $('.graph-preloader').css('background', 'none');
    $("#deptwise_currmonthsalary").prepend("<div class='no-data text-center'><span class='text-red'> No Data</span></div>");
}
else{
           // Instantiate and draw the chart.
           var chart = new google.visualization.ColumnChart(document.getElementById("deptwise_currmonthsalary"));
           chart.draw(view, options);
       }

   }
</script>
<script type = "text/javascript">
   google.charts.load('current', {packages: ['orgchart']});     
</script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(attActivity);

  function attActivity() {

    var data = google.visualization.arrayToDataTable([
      ['Date', 'Strength', 'AbsentCount', 'PresentCount', 'WeekendCount', 'OffCount', 'LeaveCount'],
      @php

      foreach($attactivity as $val) {
          echo "['".$val->vdate."', ".$val->strength.",".$val->abcount.", ".$val->ppcount.",  ".$val->wecount.",".$val->offcount.", ".$val->lwcount."],";
      }
      @endphp
      ]);

    var options = {
      seriesType: 'bars',
      colors: ['#0293A4','red','green','#ed7d31','#585858','#ceab2d'],
      legend: { position: "bottom" },
      'height':330,
      hAxis : { 
       textStyle : {
                    fontSize: 10 // or the number you want
                }
            },
            chartArea: {left:50,top:30,right:10},
            series: {
                1: {type: 'line'},
                2: {type: 'line'},
                3: {type: 'line'},
                4: {type: 'line'},
                5: {type: 'line'}
            }
        };
        if(data.getNumberOfRows() == 0){
            $('.graph-preloader').css('background', 'none');
            $("#att_activity").prepend("<div class='no-data text-center'><span class='text-red'> No Data</span></div>");
        }
        else{
           // Instantiate and draw the chart.
           var chart = new google.visualization.ComboChart(document.getElementById('att_activity'));
           chart.draw(data, options);
       }

   }
</script>

<script language = "JavaScript">
   function monthWiseSalary() {
            // Define the chart to be drawn.
            var data = new google.visualization.arrayToDataTable([
                ["Month", "Salary" ],
                @php
                foreach($monthWiseSalary as $data) {
                  echo "['".$data->vdate."', ".$data->salary."],";
              }
              @endphp
              ]);

            // Set chart options
            var options = { 
             'height':250,
             pointsVisible: true,
             vAxis: {format: 'short'}, 
             legend: { position: "none" },   
             chartArea: {left:40,top:20,right:10,bottom:30},
             colors:['#6859f4']
         };
         if(data.getNumberOfRows() == 0){
            $('.graph-preloader').css('background', 'none');
            $("#monthWiseSalary").prepend("<div class='no-data text-center'><span class='text-red'> No Data</span></div>");
        }
        else{
               // Instantiate and draw the chart.
               var chart = new google.visualization.LineChart(document.getElementById('monthWiseSalary'));
               chart.draw(data, options);
           }

       }
       google.charts.setOnLoadCallback(monthWiseSalary);
   </script>

   <script language = "JavaScript">
       function deptwisestrength() {
            // Define the chart to be drawn.
            var data = new google.visualization.arrayToDataTable([
                ["Month", "Strength" ],
                @php
                foreach($monthWiseStrength as $data) {
                  echo "['".$data->departmentname."', ".$data->strength."],";
              }
              @endphp
              ]);

            // Set chart options
            var options = { 
             'height':250,
             pointsVisible: true,
             legend: { position: "none" },
             hAxis : { 
               textStyle : {
                    fontSize: 9 // or the number you want
                }
            },
            chartArea: {left:40,top:20,right:10},
            colors:['#ff4e43']   
        };
        if(data.getNumberOfRows() == 0){
            $('.graph-preloader').css('background', 'none');
            $("#deptwisestrength").prepend("<div class='no-data text-center'><span class='text-red'> No Data</span></div>");
        }
        else{
               // Instantiate and draw the chart.
               var chart = new google.visualization.LineChart(document.getElementById('deptwisestrength'));
               chart.draw(data, options);
           }

       }
       google.charts.setOnLoadCallback(deptwisestrength);
   </script>
   <script language = "JavaScript">
       function deptWiseStrengthTree() {
            // Define the chart to be drawn.
            count = '<?php echo sizeof($deptWiseStrength) ;?>'; 
            if (count != 0) {

                var data = new google.visualization.arrayToDataTable([
                   ["Name", "Manager","ToolTip" ],
                   [{v:'Total', f:'Total<div>@php echo $totalstrength @endphp</div>'},'', '@php echo $totalstrength @endphp'],

                  //echo "['".$data->departmentname."', ".$data->strength."],";

                  @php
                  foreach($deptWiseStrength as $data) {

                     echo "[{v:'".$data->deptgroup."', f:'".$data->deptgroup."<div class=\"strengthcolor\">".$data->groupstrength."</div>'},'".$data->parentDept."', ".$data->totalstrength."],";
                 }
                 @endphp

                 @php
                 foreach($deptWiseStrength as $data) {

                     echo "[{v:'".$data->departmentname."', f:'".$data->departmentname.
                     "<div class=\"strengthcolor\">".$data->strength."</div>'},'".$data->deptgroup."', ".$data->strength."],";
                 }
                 @endphp
                 ]);     

                // Set chart options
                var options = {allowHtml:true, allowCollapse:false};
                if(data.getNumberOfRows() == 0){
                    $('.graph-preloader').css('background', 'none');
                    $("#deptWiseStrengthTree").prepend("<div class='no-data text-center'><span class='text-red'> No Data</span></div>");
                }
                else{
                   // Instantiate and draw the chart.
                   var chart = new google.visualization.OrgChart(document.getElementById('deptWiseStrengthTree'));
                   chart.draw(data, options);
               }      

           }
           else{
            $('.graph-preloader').css('height', 'auto');
            $("#deptWiseStrengthTree").prepend("<div class='no-data text-center'><span class='text-red'> No Data</span></div>");
        }
    }  
    google.charts.setOnLoadCallback(deptWiseStrengthTree);
</script>
@endpush
@endsection