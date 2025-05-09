@extends('layouts.main') 
@section('title', 'Loan Deduction')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush

<div class="container-fluid">
    <!-- start message area-->
    @include('include.message')
    <!-- end message area-->
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-border">
                <form class="forms-sample" method="POST"  id ="loanDeductionForm"  action="{{ route('employee-loan-deduction-save') }}" >
                    <div class="card-header">
                        <div class="header-title col-sm-4">
                           <span><h3 class="title-heading">{{ __('Loan Deduction')}}</h3></span>
                       </div>
                       <div class="col-sm-8 header-btns">
                        <a href="#" class="btn btn-fetch btn-secondary" id="fetchbtn">{{ __('Fetch')}}</a>
                        <button  id="save-button" class="btn btn-success">{{ __('Save')}}</button>
                        <a type="submit" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
                    </div>
                </div>
                <div class="card-body">
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label for="loanType" class="col-form-label">{{ __('Department')}}</label>
                            <div>
                                {!! Form::select('deptid',$allDepts, null, ['class'=>'form-control select2', 'id'=> 'deptid', 'placeholder'=> '--- Select Department ---', 'required'=> 'required']) !!}
                            </div>
                            <div class="help-block with-errors"></div>

                            @error('loanType')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>                    

                        <div class="form-group col-sm-3">
                            <label for="vdatein" class="col-form-label">{{ __('Month')}}</label>
                            <input name="vdatein" id="vdatein" type="month" class="form-control input-md" width="276" required="" value="{{old('vdatein')}}" />
                        </div>
                        <input type="hidden" id="token" name="token" value="{{ csrf_token() }}">

                    </div>

                    @php $data = Session::get('data');
                    @endphp
                    @if(is_array($data) || is_object($data))
                    <div class="row mt-10" id="empLoanDeduction">
                        <div class="col-md-12">
                            <table id="loanDeductionTable" class="table table-responsive-lg table-bordered">
                                <thead>
                                    <tr>        
                                        <th>{{ __('Employee')}}</th>
                                        <th>{{ __('Loan Date')}}</th>
                                        <th>{{ __('Loan amount')}}</th>
                                        <th>{{ __('Installment')}}</th>
                                        <th>{{ __('Balance')}}</th>
                                        <th>{{ __('Deduction Date')}}</th>
                                        <th>{{ __('Deduction Amount')}}</th>
                                        <th>{{ __('Remarks')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i = 1
                                    @endphp
                                    @if(count($data))
                                    @foreach ($data as $value)
                                    <input type="hidden" value="{{ $value->id }}" name="data[{{$i}}][id]" size="1" />
                                    <input type="hidden" value="{{ $value->employeeid }}" name="data[{{$i}}][employeeid]" size="1" />
                                    <input type="hidden" class="form-control" value="{{ $value->loanid }}" name="data[{{$i}}][loanid]"/>
                                    <input type="hidden" class="form-control" value="{{ $value->loanvno }}" name="data[{{$i}}][loanvno]"/>
                                    <input type="hidden" class="form-control" value="{{ $value->refno }}" name="data[{{$i}}][refno]"/>
                                    <input type="hidden" class="form-control" value="{{ $value->datein }}" name="data[{{$i}}][datein]"/>
                                    <input type="hidden" class="form-control" value="{{ $value->balance }}" name="data[{{$i}}][balance]"/>
                                    <input type="hidden" class="form-control" value="{{ $value->loandate }}" name="data[{{$i}}][loandate]"/>
                                    <input type="hidden" class="form-control" value="{{ $value->loanamount }}" name="data[{{$i}}][loanamount]"/>
                                    <input type="hidden" class="form-control" value="{{ $value->installment }}" name="data[{{$i}}][installment]"/>
                                    <input type="hidden" class="form-control" value="{{ $value->installment }}" name="data[{{$i}}][installment]"/>
                                    <tr>
                                        <td>{{$value->empcode.' - '}}{{$value->employeename}}</td>
                                        <td>{{$value->loandate}}</td>
                                        <td>{{$value->loanamount}}</td>
                                        <td>{{$value->installment}}</td>
                                        <td>{{$value->balance}}</td>
                                        <td>{{$value->filldate}}</td>
                                        <td>
                                            <input id="amount" type="text" class="form-control" name="data[{{$i}}][amount]" value="{{$value->amount}}" onkeydown="return event.charCode != 45 && event.keyCode !== 69 && event.keyCode !== 189">
                                        </td>
                                        <td><input type="text" name="data[{{$i}}][remarks]" class="form-control" id="remarks" value="{{ $value->remarks }}"></td>
                                    </tr>
                                    @php
                                    $i++
                                    @endphp
                                    @endforeach
                                    @else
                                    <td colspan="7" align="center">No Record Found </td>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<!-- push external js -->
@push('script') 
<script src="{{ asset('js/hrpayroll/employee/loan_deduction.js') }}"></script> 
<script type="text/javascript">
$('#cancelBtn').click(function() {
    location.reload();
});
</script>
@endpush
@endsection
