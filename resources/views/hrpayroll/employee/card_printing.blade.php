@extends('layouts.main') 
@section('title', 'Employee Card Printing')
@section('content')
<!-- push external head elements to head -->
@push('head')
<style type="text/css">
    .hide_column{
        display: none;
    }
</style>
<link rel="stylesheet" href="{{ asset('plugins/jquery-toast-plugin/dist/jquery.toast.min.css')}}">
@endpush
<div class="container-fluid">
    <!-- start message area-->
    @include('include.message')
    <!-- end message area-->
    <div class="row"> 
    <div class="offset-sm-1 col-sm-10">   
        <div class="card card-border">
            <form class="forms-sample" method="POST" action="" >
                <div class="card-header">
                    <div class="col-sm-5 header-title">
                        <h3>{{ __('Employee Card Printing')}}</h3>
                    </div>
                    <div class="col-7 header-btns">
                        <a class="btn btn-secondary" id="printBtn" onclick="getCheckedCells()">{{ __('Print')}}</a> 
                        <a class="btn btn-success" id="fillBtn">{{ __('Fetch')}}</a>
                        <a class="btn btn-warning" id="cardCancelBtn" >{{ __('Cancel')}}</a> 
                    </div>
                </div>
                <div class="card-body"> 
                    @csrf                                              
                        <div class="form-group row">

                            <div class="col-md-3">
                                <label for="locationid" class="col-form-label">{{ __('Location')}}</label>
                                {!! Form::select('locationid',$allLocations, null, ['class'=>'form-control select2', 'id'=> 'locationid','placeholder'=>'--- Select Location ---']) !!}
                           </div>

                            <div class="col-md-3">
                                <label for="deptid" class="col-form-label">{{ __('Department')}}</label>
                                {!! Form::select('deptid',$allDepts, null, ['class'=>'form-control select2', 'id'=> 'deptid','placeholder'=>'--- Select Department ---']) !!}
                            </div>

                            <div class="col-md-3">
                               <label for="etypeid" class="col-form-label">{{ __('E-Type')}}</label>
                               {!! Form::select('etypeid',$allEtypes, null, ['class'=>'form-control select2', 'id'=> 'etypeid']) !!}
                           </div>

                            <div class="col-md-2">                              
                                <label for="alldepts" class="col-form-label">{{ __('All Departments')}}</label> 
                                 <span class="form-control input-sm input-checkbox"><input id="alldepts" type="checkbox" name="alldepts" value=""></span>               
                            </div>
                        </div>
                        <br>
                        <div id="select-emp-err" style="display:none; color: red;">Select Some Employees</div>
                        <input name="id" id="id" type="hidden" value="{{old('id')}}"/>
                    </form>
                </div>
         </div>
     </div>
    </div>
    <div class="row" id="cardPrinting" style="display: none;"> 
        <div class="offset-lg-1 col-lg-10 col-sm-12">   
            <div class="card p-3 card-border">
              <div class="card-body table-responsive-sm">
                <table id="cardPrintingTable" class="table" >
                    <thead>
                        <tr>       
                            <th><input type="checkbox" name="select-all-checkboxes" id="select-all-checkboxes"></th>  
                            <th>{{ __('Emp id')}}</th>         
                            <th>{{ __('Employee Code')}}</th>
                            <th>{{ __('Employee Name')}}</th>
                            <th>{{ __('Department')}}</th>  

                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</div>
<!-- push external js -->
@push('script') 
<script src="{{ asset('js/hrpayroll/employee/card_printing.js') }}"></script> 
<script src="{{ asset('js/alerts.js')}}"></script>
<script src="{{ asset('plugins/jquery-toast-plugin/dist/jquery.toast.min.js')}}"></script>
<script type="text/javascript">
     function getCheckedCells() {
        var dTableCardPrinting = $('#cardPrintingTable').DataTable();
        var selectedCells = "";
        var empid = "";
        var prepend ="";

        $("#cardPrintingTable tbody input[type=checkbox]:checked").each(function () {
            var row = $(this).closest("tr")[0];
            empid = row.cells[1].innerHTML;
            selectedCells = selectedCells.concat(prepend,empid);
            empid = "";
            prepend =",";
        });
        if(selectedCells.length == 0 ){
            document.getElementById("select-emp-err").style.display = "block";
            return;
        }
        
        $.ajax({
            url: 'card-printing/setEmpIdsInSession',
            method: 'get',
            data: {'selectedCells': selectedCells},

            success:function(data){
              window.open("card-printing/print", "_blank", ",left=100,width=1200,height=800");
            },
            error: function(xhr, status, error) {

                $('.alert').show();
                $('.alert').addClass('error');
                $('.alert_msg').text(xhr.responseText);

            }

        });
    }
</script>


@endpush
@endsection
