@extends('layouts.main') 
@section('title', 'Closing Month Cheque')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush
<!-- start message area-->
@include('include.message')
<!-- end message area-->
<div class="row">
    <div class="col-sm-12">
        <div class="card card-border">
            <form class="forms-sample" method="POST" action="{{url('employee/closing-month-cheque/save')}}" >
                <div class="card-header">
                    <div class="col-sm-5 header-title">
                        <h3>{{ __('Closing Month Cheque')}}</h3>
                    </div>
                    <div class="col-7 header-btns">
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
                                <label for="employeeid" class="col-form-label">{{ __('Employee Name')}}</label>
                                {!! Form::select('employeeid', $empNames, null,[ 'class'=>'form-control select2', 'id'=> 'employeeid', 'placeholder'=> '--- Select Employee ---', 'required'=> 'required']) !!}
                            </div> 
                            <div class="col-sm-4">
                                <label for="commission_type" class="col-form-label">{{ __('VType')}}</label>
                                <div>
                                    {!! Form::select('vtype', $vtype, null,[ 'class'=>'form-control select2', 'id'=> 'vtype', 'required'=> 'required','placeholder' =>'--- Select VType ---']) !!}
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label for="vdate" class="col-form-label">{{ __('Month Date')}}</label>
                                <input name="vdate" id="vdate" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" value="{{old('vdate')}}" />
                            </div> 
                        </div> 

                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="bankid" class="col-form-label">{{ __('Bank')}}</label>
                                {!! Form::select('bankid', $banks, null,[ 'class'=>'form-control select2', 'id'=> 'bankid', 'required'=> 'required', 'placeholder'=> '--- Select ---']) !!}
                            </div>

                            <div class="col-sm-2">
                                <label for="chequeno" class="col-form-label">{{ __('ChequeNo')}}</label>
                                <input id="chequeno" type="text" class="form-control @error('chequeno') is-invalid @enderror" name="chequeno" value="{{old('chequeno')}}" placeholder="Cheque No" required>
                            </div>  

                            <div class="col-sm-2">
                                <label for="chequedate" class="col-form-label">{{ __('Cheque Date')}}</label>
                                <input name="chequedate" id="chequedate" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" value="{{old('chequedate')}}" />     
                            </div>

                            <div class="col-sm-2">
                                <label for="chequeamount" class="col-form-label">{{ __('Amount')}}
                                </label>
                                <input id="chequeamount" type="number" class="form-control @error('chequeamount') is-invalid @enderror" name="chequeamount" value="{{old('chequeamount')}}" placeholder="00.00" required onkeydown="return event.charCode != 45 && event.keyCode !== 69 && event.keyCode !== 189">
                                <span id="erridname" class="text-red"></span>
                            </div>

                            <div class="col-sm-2">
                                <label for="remarks" class="col-form-label">{{ __('Remarks')}}</label>
                                <input id="remarks" type="text" class="form-control @error('remarks') is-invalid @enderror" name="remarks" value="{{old('remarks')}}" placeholder="Remarks" required>               
                            </div>  
                        </div>

                    </div>
                </div>
                <input name="id" id="id" type="hidden" value="{{old('id')}}"/>
            </div>
        </form>
    </div>
</div>
</div>

<div class="row">
  <div class="col-sm-12">
     <div class="card p-3 card-border">
        <div class="card-body table-responsive-sm">
           <table id="closingMonthChequeTable" class="table table-bordered table-striped table-hover">
              <thead>
                 <tr>           
                    <th>{{ __('Employee')}}</th>
                    <th>{{ __('VType')}}</th>
                    <th>{{ __('Bank')}}</th>
                    <th>{{ __('Cheque No.')}}</th>
                    <th>{{ __('Cheque Date')}}</th>
                    <th>{{ __('Cheque Amount')}}</th>
                    <th>{{ __('Remarks')}}</th>
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

<!-- push external js -->
@push('script') 
<script src="{{ asset('js/hrpayroll/employee/closing_month_cheque.js') }}"></script> 
@endpush
@endsection
