@extends('layouts.main') 
@section('title', 'Employee Report')
@section('content')
<!-- push external head elements to head -->
@push('head')
<style type="text/css">
    div.table-responsive>div.dataTables_wrapper>div.row>div[class^="col-"]:last-child{
        padding-right: 2px !important;
    }
</style>
@endpush
<div class="container-fluid">
    <!-- start message area-->
    @include('include.message')
    <!-- end message area-->
    <div class="row">       
        <div class="card card-border">
            <form class="forms-sample" method="POST" action="#" >
                <div class="card-header">
                    <div class="col-sm-5 header-title">
                        <h3>{{ __('Employee Report')}}</h3>
                    </div>
                    <div class="offset-sm-2 col-sm-5 header-btns">
                        <a  href="#" class="btn btn-secondary" id ="fetch-record">{{ __('Fetch')}}</a>
                        <a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
                    </div>
                </div>
                <div class="card-body"> 
                    @csrf                                              
                    <div class="form-group row">
                        <label for="deptid" class="col-sm-2 col-form-label">{{ __('EType')}}</label>
                        {!! Form::select('etypeid',$allEtypes, null, ['class'=>'form-control select2 col-sm-2', 'id'=> 'etypeid', 'placeholder' => '--- Select ---']) !!}

                        <label for="deptid" class="col-sm-2 col-form-label">{{ __('Location')}}</label>
                        {!! Form::select('locationid',$allLocations, null, ['class'=>'form-control select2 col-sm-2', 'id'=> 'locationid', 'placeholder' => '--- Select ---']) !!}


                        <label for="deptid" class="col-sm-2 col-form-label">{{ __('Department')}}</label>
                        <div class="col-sm-2">
                           {!! Form::select('deptid',$allDepts, null, ['class'=>'form-control select2 ', 'id'=> 'deptid']) !!} 
                       </div>
                   </div>
                    <div class="form-group row">
                       <label for="desgid" class="col-sm-2 col-form-label">{{ __('Designation')}}</label>
                       {!! Form::select('desgid',$allDesgs, null, ['class'=>'form-control select2 col-sm-2', 'id'=> 'desgid', 'placeholder' => '--- Select ---']) !!}

                       <label for="genderid" class="col-sm-2 col-form-label">{{ __('Gender')}}</label>
                       {!! Form::select('genderid',$allGenders, null, ['class'=>'form-control select2 col-sm-2', 'id'=> 'genderid', 'placeholder' => '--- Select ---']) !!}


                       <label for="religionid" class="col-sm-2 col-form-label">{{ __('Religion')}}</label>
                       <div class="col-sm-2">
                        {!! Form::select('religionid',$allReligions, null, ['class'=>'form-control select2', 'id'=> 'religionid', 'placeholder' => '--- Select ---']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    <label for="jobtypeid" class="col-sm-2 col-form-label">{{ __('Probation Status')}}</label>
                    {!! Form::select('jobtypeid',$allProbationStatus, null, ['class'=>'form-control select2 col-sm-2', 'id'=> 'jobtypeid', 'placeholder' => '--- Select ---']) !!}

                    <label for="ishod" class="col-sm-2 col-form-label">{{ __('Is HOD')}}</label>
                    {!! Form::select('ishod',$bool, null, ['class'=>'form-control select2 col-sm-2', 'id'=> 'ishod', 'placeholder' => '--- Select ---']) !!}

                    <label for="isshiftemployee" class="col-sm-2 col-form-label">{{ __('Shift Employee')}}</label>
                    <div class="col-sm-2">
                        {!! Form::select('isshiftemployee',$bool, null, ['class'=>'form-control select2', 'id'=> 'isshiftemployee', 'placeholder' => '--- Select ---']) !!}
                    </div>
                </div>
                 <div class="form-group row">
                    <label for="ginsurance" class="col-sm-2 col-form-label">{{ __('G-Insurance')}}</label>
                    {!! Form::select('ginsurance',$bool, null, ['class'=>'form-control select2 col-sm-2','id'=> 'ginsurance', 'placeholder' => '--- Select ---']) !!}

                    <label for="haveot" class="col-sm-2 col-form-label">{{ __('Have O/T')}}</label>
                    {!! Form::select('haveot',$bool, null, ['class'=>'form-control select2 col-sm-2', 'id'=> 'haveot', 'placeholder' => '--- Select ---']) !!}


                    <label for="issalarytobank" class="col-sm-2 col-form-label">{{ __('Salary to Bank')}}</label>
                    <div class="col-sm-2">
                        {!! Form::select('issalarytobank',$bool, null, ['class'=>'form-control select2', 'id'=> 'issalarytobank', 'placeholder' => '--- Select ---']) !!}
                    </div>
                </div>
                 <div class="form-group row">
                    <label for="leftstatusid" class="col-sm-2 col-form-label">{{ __('Left Status')}}</label>                             
                    {!! Form::select('leftstatusid',$allLeftStatus, null, ['class'=>'form-control select2 col-sm-2', 'id'=> 'leftstatusid', 'placeholder' => '--- Select ---']) !!}

                    <label for="cnicno" class="col-sm-2 col-form-label">{{ __('Name, Father, Blood, NIC')}}</label>
                    <div class="col-sm-6 pl-0">
                        <input name="cnicno" id="cnicno" type="text" class="form-control input-sm" width="276" />
                    </div>
                </div>
                <br>
                <div class="emp-report-filters">  
                   <div class="form-group row">

                    <div class="col-sm-3 box-bl">
                        <label for="dojfrom" class="col-form-label">{{ __('Select DOJ')}}</label>
                        <input name="dojfrom" id="dojfrom" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" />
                        <label for="dojto" class="col-form-label">TO</label>
                        <input name="dojto" id="dojto" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" />
                    </div>

                    <div class="col-sm-3 box-bl">
                        <label for="dolfrom" class="col-form-label">{{ __('Select DOL')}}</label>
                        <input name="dolfrom" id="dolfrom" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" />
                        <label for="dolto" class="col-form-label">TO</label>
                        <input name="dolto" id="dolto" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" />
                    </div>

                    <div class="col-sm-3 box-bl">
                     <label for="dobfrom" class="col-form-label">{{ __('Select DOB')}}</label>
                     <input name="dobfrom" id="dobfrom" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" />
                     <label for="dobto" class="col-form-label">TO</label>
                     <input name="dobto" id="dobto" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" />
                 </div>

                 <div class="col-sm-3">
                     <label for="salaryfrom" class="col-form-label">{{ __('Salary Range')}}</label>
                     <input name="salaryfrom" id="salaryfrom" type="number" class="form-control input-sm" width="276" />                
                     <label for="salaryto" class="col-form-label">TO</label>
                     <input name="salaryto" id="salaryto" type="number" class="form-control input-sm" width="276" />
                 </div>

             </div>
         </div>
         <br>
         <div class="emp-report-filter">
            <div class="form-group row">
               <div class="form-radio col-sm-11">
                 <div class="radio radio-inline">
                    <label>
                        <input type="radio" name="attfilter" checked="checked" value="empdetailreport">
                        <i class="helper"></i>Employee Detail Report
                    </label>
                </div>

                <div class="radio radio-inline">
                    <label>
                        <input type="radio" name="attfilter" value="deptwisestrength">
                        <i class="helper"></i>Department Wise Strength
                    </label>
                </div>
                <div class="radio radio-inline">
                    <label>
                        <input type="radio" name="attfilter" value="desgwisestrength">
                        <i class="helper"></i>Designation Wise Strength
                    </label>
                </div>

                <div class="radio radio-inline">
                    <label>
                        <input type="radio" name="attfilter" value="empcardreport">
                        <i class="helper"></i>Employee Card Report
                    </label>
                </div>
            </div>
        </div>
    </div>
    <input name="id" id="id" type="hidden" value=""/>
    <input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
</div>
</form>
</div>
</div>

<div class="row" style="display: none;" id="empDetail">
    <div class="card p-3 card-border">
        <div class="card-body  table-responsive">
            <table id="empDetailTable" class="table table-striped" style="width: 150% !important">
                <thead>
                    <tr>           
                        <th>{{ __('Employee')}}</th>
                        <th>{{ __('Department')}}</th>
                        <th>{{ __('Designation')}}</th>
                        <th>{{ __('DOB')}}</th>
                        <th>{{ __('DOJ')}}</th>
                        <th>{{ __('Salary')}}</th>
                        <th>{{ __('Shift')}}</th>
                        <th>{{ __('TimeIn')}}</th>
                        <th>{{ __('TimeOut')}}</th>
                        <th>{{ __('CNIC')}}</th>
                        <th>{{ __('Company')}}</th>

                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row" style="display: none;" id="deptWiseStrength">
    <div class="card p-3 card-border">
        <div class="card-body">
            <table id="deptWiseTable" class="table table-striped table-responsive" style="    width: calc(100% + 40px) !important;">
                <thead>
                    <tr>           
                        <th>{{ __('Employee Code')}}</th>
                        <th>{{ __('Employee Name')}}</th>
                        <th>{{ __('Employee Department')}}</th>
                        <th>{{ __('Strenth')}}</th>

                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row" style="display: none;" id="desgWiseStrength">
    <div class="card p-3 card-border">
        <div class="card-body">
            <table id="desgWiseTable" class="table table-striped table-responsive" style="    width: calc(100% + 40px) !important;">
                <thead>
                    <tr>
                        <th>{{ __('Employee Code')}}</th>           
                        <th>{{ __('Employee Name')}}</th>
                        <th>{{ __('Employee Designation')}}</th>
                        <th>{{ __('Strenth')}}</th>

                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<!-- push external js -->
@push('script') 
<script src="{{ asset('js/hrpayroll/employee/reports/emp_detail_report.js') }}"></script> 
@endpush
@endsection
