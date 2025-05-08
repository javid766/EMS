<?php  set_time_limit(1200);?>
<! DOCTYPE html>
<html>
<head>
   <title>Employee Card Printing</title>
   <link rel="stylesheet" href="{{ asset('dist/css/theme.css') }}">

<style type="text/css">
.card-sect {
  float: left;
  width: 50%;
  border: 1px solid #cee5ff;
  height: 200px;
  margin-bottom: 30px;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
.info {
  font-size: 10px;
  color: #02114a;
}
.info .label{
  font-weight: 700;
  padding-right: 10px;
}
.box{
   border: 1px solid black;
   padding:6px 13px 6px 7px; 
   height: 170px;
}
.location-text{
   font-size: 8px;
   padding-bottom: 10px;
}
.emp-img img{
    width: 80px; 
    height: 80px; 
}
.emp-img {
  width: 80px; 
  height: 80px;
  border: 1px solid black;
}
.emp-img .placeholder{
  width: 80px; 
  height: 77px;
  margin-top: 0.3px;
}
.sign{
    border-top: 1px solid black;
    width:110px;
    text-align: center; 
    font-size: 10px;
}
.card-default-txt{
    font-size: 8px;
    font-style: italic;
    padding-right:15px; 
    margin-bottom: 30px;
}
</style>

</head>

<body>
      <?php foreach ($empcarddata as $value) {
            $imgsrc = '';
            $url = url('/'); 
            $date = strtotime($value['doj']);
            $doj = date("M d, Y" , $date);
            $phoneno = $value['contactno'];
            $address = $value['address'];
            if(!$phoneno){
              $phoneno= "N/A";
            }
            if(!$address){
              $address= "N/A";
            }
            if($value['emppic']){
              $emppic = $value['emppic'];
              $imgsrc = '/'.$emppic; 
            } 
            ?>
        <div class="emp-card row">
            <div class="card-sect" style="margin-right: 3px;">
              <div style="padding:7px 7px;">
                <div class="box">
               <div style="font-weight: 600;font-size: 15px;">{{$value['company']}}</div>
               <div class="location-text">{{$value['address']}}</div>

              <div class="row">
                <div class="emp-img" style="float: right;">
                <?php if($imgsrc){?>
                  <img src="{{ $url.$imgsrc ?? '' }}">
                <?php }
                else{?>
                  <img class="placeholder" src="{{ $url.'/images/person-placeholder.png' }}">
                 <?php }?>
                
                </div>
                <div class="info">
                   <table>
                      <tr>
                        <td class="label">Department </td>
                        <td>{{$value['department']}}</td>
                      </tr>
                      <tr>
                        <td class="label">Employee # </td>
                        <td>{{$value['empcode']}}</td>
                      </tr>
                      <tr>
                        <td class="label">Name </td>
                        <td>{{$value['employeename']}}</td>
                      </tr>
                      <tr>
                        <td class="label">F.Name </td>
                        <td>{{$value['fathername']}}</td>
                      </tr>
                      <tr>
                        <td class="label">Designation </td>
                        <td>{{$value['designation']}}</td>
                      </tr>
                   </table>
                </div>
              </div>
            </div>
            </div>
          </div>
            <div class="card-sect">
               <div style="padding:7px 7px;">
                  <div  class="box">
                    <table class="info">
                      <tr>
                        <td class="label">Address</td>
                        <td>{{$address}}</td>
                      </tr>
                      <tr>
                        <td class="label">Phone # </td>
                        <td>{{$phoneno}}</td>
                      </tr>
                      <tr>
                        <td class="label">N.I.C </td>
                        <td>{{$value['cnicno']}}</td>
                      </tr>
                      <tr>
                        <td class="label">DOJ </td>
                        <td>{{$doj}}</td>
                      </tr>
                    </table>
                    <p class="card-default-txt"><b>This card is the property of {{$value['company']}} , and is
                    non transferable. If found mail it to {{$value['location']}}</b>
                    </p>
                    <div class="sign">
                      Issuing Authority 
                    </div>
                 </div>
                </div>
               </div>
            </div>
        </div>
  <?php } ?>
</body>
</html>

