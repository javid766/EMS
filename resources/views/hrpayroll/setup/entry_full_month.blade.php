@extends('layouts.main') 
@section('title', 'Entry Full Month')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush


<div class="container-fluid">
    <!-- start message area-->
    @include('include.message')
    <!-- end message area-->
    <div class="offset-3 col-6">
        <div class="card card-border">
            <div class="card-header">
                <div class="col-5 header-title">
                    <h3>{{ __('Entry Full Month')}}</h3>
                </div>
                <div class="offset-2 col-5 header-btns">
                    <button  id="save-button" class="btn btn-success">{{ __('Save')}}</button>
                    <button type="submit" class="btn btn-warning" id="cancel-button" >{{ __('Cancel')}}</button> 
                </div>
            </div>
            <div class="card-body">
                <form class="forms-sample" method="POST" action="" >
                    @csrf
                    <div class="row">
                        <div class="col-sm-12">

                            <div class="form-group row">
                                <label for="emp_code" class="col-sm-4 col-form-label">{{ __('Employee Code')}}</label>
                                <div class="col-sm-8">
                                   <input id="emp_code" type="text" class="form-control @error('emp_code') is-invalid @enderror" name="emp_code" value="" placeholder="Employee Code" required>
                               </div>
                               <div class="help-block with-errors"></div>

                               @error('emp_code')
                               <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label for="emp_name" class="col-sm-4 col-form-label">{{ __('Employee Name')}}</label>
                            <div class="col-sm-8">
                             <input id="emp_name" type="text" class="form-control @error('emp_name') is-invalid @enderror" name="emp_name" value="" placeholder="Employee Name" required>
                         </div>
                         <div class="help-block with-errors"></div>

                         @error('emp_name')
                         <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="leaveType" class="col-sm-4 col-form-label">{{ __('Leave Type')}}</label>
                        <div class="col-sm-8">
                            {!! Form::select('type', $leaveType, null,[ 'class'=>'form-control select2', 'placeholder' => 'Select Leave Type','id'=> 'leaveType', 'required'=> 'required']) !!}
                        </div>
                        <div class="help-block with-errors"></div>

                        @error('leaveType')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="leave_from" class="col-sm-4 col-form-label">{{ __('Date From')}}</label>
                        <div class="col-sm-8">
                            <input name="leave_from" id="leave_from" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" />
                        </div>
                        <div class="help-block with-errors"></div>

                        @error('leave_from')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>  

                    <div class="form-group row">
                        <label for="leave_to" class="col-sm-4 col-form-label">{{ __('Date To')}}</label>
                        <div class="col-sm-8">
                            <input name="leave_to" id="leave_to" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" />
                        </div>
                        <div class="help-block with-errors"></div>

                        @error('leave_to')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    
                    <div class="form-group row">
                        <label for="remarks" class="col-sm-4 col-form-label">{{ __('Remarks')}}</label>
                        <div class="col-sm-8">
                           <input id="remarks" type="text" class="form-control @error('remarks') is-invalid @enderror" name="remarks" value="" placeholder="Remarks" required>
                       </div>
                       <div class="help-block with-errors"></div>

                       @error('remarks')
                       <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>              
            </div>

            <input type="hidden" id="token" name="token" value="{{ csrf_token() }}">

        </div>

    </form>
</div>
</div>
</div>

</div>
<!-- push external js -->
@push('script') 

@endpush
@endsection
