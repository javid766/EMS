@extends('layouts.main') 
@section('title', 'Loan')
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
                <form class="forms-sample" method="POST" action="{{url('employee/loan/save')}}">
                    <div class="card-header">
                        <div class="col-sm-5 header-title">
                            <h3>{{ __('Loan')}}</h3>
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
                                    <label for="allowdedid" class="col-form-label">{{ __('Loan Type')}}</label>
                                    <div>
                                        {!! Form::select('allowdedid', $loanType, null,[ 'class'=>'form-control select2', 'id'=> 'allowdedid', 'required'=> 'required', 'placeholder'=> '--- Select Loan Type ---']) !!}
                                    </div>
                                </div>  

                                <div class="col-sm-4">
                                  <label for="employeeid" class="col-form-label">{{ __(' Employee')}}</label>
                                  {!! Form::select('employeeid', $employees, null,[ 'class'=>'form-control select2', 'id'=> 'employeeid', 'placeholder'=> '--- Select Employee ---', 'required'=> 'required']) !!}
                               </div>  

                                <div class="col-sm-4">
                                    <label for="datein" class="col-form-label">{{ __('Date')}}</label>
                                    <input name="datein" id="datein" type="date" class="form-control input-sm" width="276" required="" value="{{ old('datein') }}" />
                                </div> 
                            </div> 

                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="bankid" class="col-form-label">{{ __('Bank')}}</label>
                                {!! Form::select('bankid', $banks, null,[ 'class'=>'form-control select2', 'id'=> 'bankid','placeholder'=> '--- Select Bank ---' ,'required'=> 'required']) !!}
                            </div>

                            <div class="col-sm-2">
                                <label for="chequeno" class="col-form-label">{{ __('ChequeNo')}}</label>
                                <input id="chequeno" type="text" class="form-control" name="chequeno" value="{{ old('chequeno') }}" placeholder="Cheque No" required>
                            </div>  

                            <div class="col-sm-2">
                                <label for="chequedate" class="col-form-label">{{ __('Cheque Date')}}</label>
                                <input name="chequedate" id="chequedate" type="date" class="form-control input-sm" width="276" required="" value="{{ old('chequedate') }}"/>     
                            </div>

                            <div class="col-sm-2">
                                <label for="amount" class="col-form-label">{{ __('Amount')}}
                                </label>
                                <input id="amount" type="number" class="form-control" name="amount" value="{{ old('amount') }}" step="0.01" placeholder="00" min="0" onkeydown="return event.charCode != 45 && event.keyCode !== 69 && event.keyCode !== 189" required>
                               
                            </div>

                            <div class="col-sm-2">
                                <label for="installment" class="col-form-label">{{ __('Installment')}}
                                </label>
                                <input id="installment" type="number" class="form-control" name="installment"  value="{{ old('installment') }}"placeholder="00" step="0.01" min="0" onkeydown="return event.charCode != 45 && event.keyCode !== 69 && event.keyCode !== 189" required>
                                
                            </div>

                           
                        </div>

                         <div class="form-group row">
                        	<div class="col-sm-4">
                                <label for="remarks" class="col-form-label">{{ __('Remarks')}}</label>
                                <input id="remarks" type="text" class="form-control" name="remarks" value="{{ old('remarks') }}" placeholder="Remarks" required>               
                            </div>  
                        </div>


                        </div>
                    </div>
                        <input name="id" id="id" type="hidden" value="{{ old('id') }}"/>
                        <input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
                    </div>
                </form>
            </div>
        </div>
    </div>

<div class="row">
  <div class="col-sm-12">
     <div class="card p-3 card-border">
        <div class="card-body">
           <table id="loanEntryTable" class="table table-bordered table-striped table-hover">
              <thead>
                 <tr> 
                  	<th>{{ __('Loan Type')}}</th>          
                    <th>{{ __('Employee')}}</th>                 
                    <th>{{ __('Date')}}</th>
                    <th>{{ __('Cheque No.')}}</th>
                    <th>{{ __('Cheque Date')}}</th>
                    <th>{{ __('Amount')}}</th>
                    <th>{{ __('Installment')}}</th>
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
<script src="{{ asset('js/hrpayroll/employee/loan_entry.js') }}"></script> 
<script type="text/javascript">
     function validateForm(){
        var amount =  document.getElementById('amount').value ;
        var installment =  document.getElementById('installment').value ;
        var flag = true;
        if (amount < 0) {
            document.getElementById('amounterr').innerHTML = 'Enter value greater than -1';
            flag = false;
        }
        else{
            document.getElementById('amounterr').innerHTML = '';
            flag = true; 
        }
        if (installment < 0 ) {
            document.getElementById('installmenterr').innerHTML = 'Enter value greater than -1';
            flag = false;  
        }
        else{
            document.getElementById('installmenterr').innerHTML = '';
            flag = true; 
        }
        return flag;
    }
</script>
@endpush
@endsection
