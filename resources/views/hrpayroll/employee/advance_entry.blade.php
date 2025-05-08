@extends('layouts.main') 
@section('title', 'Advance Against Salary')
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
                <form class="forms-sample" method="POST" action="{{url('employee/advance/save')}}">
                    <div class="card-header">
                        <div class="col-sm-5 header-title">
                            <h3>{{ __('Advance Against Salary')}}</h3>
                        </div>
                        <div class="col-7 header-btns">
                            <button  id="saveBtn" class="btn btn-success">{{ __('Save')}}</button>
                            <a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
                        </div>
                    </div>
                    <div class="card-body"> 
                        @csrf
                        <div class="row">

                            <div class="col-sm-12">

                             <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="empid" class="col-form-label">{{ __(' Employee')}}</label>
                                  {!! Form::select('employeeid', $employees, null,[ 'class'=>'form-control select2', 'id'=> 'employeeid', 'placeholder'=> '--- Select Employee ---', 'required'=> 'required']) !!}
                                </div>  

                                <div class="col-sm-3">
                                 <label for="datein" class="col-form-label">{{ __('Date')}}</label>
                                    <input name="datein" id="datein" type="date" value="{{old('datein')}}" class="form-control input-sm" width="276" required="" />
                               </div>  

                                <div class="col-sm-5">
                                    <label for="remarks" class="col-form-label">{{ __('Remarks')}}</label>
                                    <input id="remarks" type="text" class="form-control" name="remarks" value="{{ old('remarks') }}" placeholder="Remarks" required>          
                                </div>  
                            </div> 

                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="bankid" class="col-form-label">{{ __('Bank')}}</label>
                                {!! Form::select('bankid', $banks, null,[ 'class'=>'form-control select2', 'id'=> 'bankid','placeholder'=> '--- Select Bank ---' ,'required'=> 'required']) !!}
                            </div>

                            <div class="col-sm-3">
                                <label for="chequeno" class="col-form-label">{{ __('ChequeNo')}}</label>
                                <input id="chequeno" type="text" class="form-control" name="chequeno" value="{{ old('chequeno') }}" placeholder="Cheque No" required>
                            </div>  

                            <div class="col-sm-2">
                                <label for="chequedate" class="col-form-label">{{ __('Cheque Date')}}</label>
                                <input name="chequedate" id="chequedate" type="date" value="{{old('chequedate')}}" class="form-control input-sm" width="276" required="" />     
                            </div>

                            <div class="col-sm-3">
                                <label for="amount" class="col-form-label">{{ __('Amount')}}
                                </label>
                                <input id="amount" type="number" class="form-control" name="amount" value="{{ old('amount') }}" step="0.01" placeholder="00" onkeydown="return event.charCode != 45 && event.keyCode !== 69 && event.keyCode !== 189" required>
                           </div>                           
                        </div>

                        </div>
                    </div>
                        <input name="id" id="id" type="hidden" value="{{old('id')}}"/>
                        <input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
                    </div>
                </form>
            </div>
        </div>
    </div>

<div class="row" id="advanceEntry">
  <div class="col-sm-12">
     <div class="card p-3 card-border">
        <div class="card-body table-responsive-sm">
           <table id="advanceEntryTable" class="table table-bordered table-striped table-hover">
              <thead>
                 <tr> 
                    <th>{{ __('Employee')}}</th>     
                    <th>{{ __('Date')}}</th>             
                    <th>{{ __('Cheque No.')}}</th>
                    <th>{{ __('Cheque Date')}}</th>
                    <th>{{ __('Amount')}}</th>
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
<script src="{{ asset('js/hrpayroll/employee/advance_entry.js') }}"></script> 
@endpush
@endsection
