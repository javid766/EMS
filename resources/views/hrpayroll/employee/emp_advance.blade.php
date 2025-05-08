@extends('layouts.main') 
@section('title', 'Advance Against Salary')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush
    <!-- start message area-->
    @include('include.message')
    <!-- end message area-->
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-border">
                <form class="forms-sample" method="POST" action="{{url('setup/religion/save')}}" >
                    <div class="card-header">
                        <div class="col-sm-5 header-title">
                            <h3>{{ __('Advance Against Salary')}}</h3>
                        </div>
                        <div class="offset-sm-2 col-sm-5 header-btns">
                            <button  id="save-button" class="btn btn-success">{{ __('Save')}}</button>
                            <a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
                        </div>
                    </div>
                    <div class="card-body"> 
                        @csrf
                        <div class="row">

                            <div class="col-sm-12">

                             <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="commission_type" class="col-form-label">{{ __('VType')}}</label>
                                    <div>
                                        {!! Form::select('vtype', $vtype, null,[ 'class'=>'form-control select2', 'id'=> 'vtype', 'required'=> 'required']) !!}
                                    </div>
                                </div>  

                                <div class="col-sm-2">
                                    <label for="emp_code" class="col-form-label">{{ __('Employee Code')}}</label>
                                    <input id="emp_code" type="text" class="form-control @error('emp_code') is-invalid @enderror" name="emp_code" value="" placeholder="Emp Code" required>
                                 </div> 

                                <div class="col-sm-4">
                                    <label for="emp_name" class="col-form-label">{{ __('Employee Name')}}</label>
                                    <input id="emp_name" type="text" class="form-control @error('emp_name') is-invalid @enderror" name="emp_name" value="" placeholder="Employee Name" required>
                                </div> 

                                <div class="col-sm-2">
                                    <label for="commission_date_to" class="col-form-label">{{ __('Month Date')}}</label>
                                    <input name="commission_date_to" id="commission_date_to" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" />
                                </div> 
                            </div> 

                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="bank" class="col-form-label">{{ __('Bank')}}</label>
                                {!! Form::select('type', $bank, null,[ 'class'=>'form-control select2', 'id'=> 'bank', 'required'=> 'required']) !!}
                            </div>

                             <div class="col-sm-2">
                                <label for="chequeNo" class="col-form-label">{{ __('ChequeNo')}}</label>
                                <input id="chequeNo" type="text" class="form-control @error('chequeNo') is-invalid @enderror" name="chequeNo" value="" placeholder="Cheque No" required>
                            </div>  

                            <div class="col-sm-2">
                                <label for="chequeDate" class="col-form-label">{{ __('Cheque Date')}}</label>
                                <input name="chequeDate" id="chequeDate" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" />     
                            </div>

                            <div class="col-sm-2">
                                <label for="amount" class="col-form-label">{{ __('Amount')}}
                                </label>
                                <input id="amount" type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" value="" placeholder="00.00" required>
                            </div>

                            <div class="col-sm-2">
                                <label for="remarks" class="col-form-label">{{ __('Remarks')}}</label>
                                <input id="remarks" type="text" class="form-control @error('remarks') is-invalid @enderror" name="remarks" value="" placeholder="Remarks" required>               
                            </div>  
                        </div>
                            
                        <input type="hidden" id="token" name="token" value="{{ csrf_token() }}">

                        </div>
                    </div>
                        <input name="id" id="id" type="hidden" value=""/>
                        <input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
                    </div>
                </form>
            </div>
        </div>
    </div>

<div class="row" style="display: none;">
  <div class="col-sm-12">
     <div class="card p-3 card-border">
        <div class="card-body">
           <table id="basicSalaryTable" class="table table-bordered table-striped table-hover">
              <thead>
                 <tr>           
                    <th>{{ __('Employee')}}</th>
                    <th>{{ __('Type')}}</th>
                    <th>{{ __('Effect')}}</th>
                    <th>{{ __('Type Detail')}}</th>
                    <th>{{ __('Cheque No.')}}</th>
                    <th>{{ __('Cheque Date')}}</th>
                    <th>{{ __('Action')}}</th>

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
<script src="{{ asset('js/hrpayroll/employee/basic_salary.js') }}"></script> 
@endpush
@endsection
