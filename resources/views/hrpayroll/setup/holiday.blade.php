@extends('layouts.main') 
@section('title', 'Holiday')
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
                <form class="forms-sample" method="POST" action="{{ route('setup-holiday-save') }}" >
                    <div class="card-header">
                        <div class="col-sm-5 header-title">
                            <h3>{{ __('Holiday')}}</h3>
                        </div>
                        <div class="col-sm-7 header-btns">
                            <button class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
                            <a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="vcode" class="col-form-label">{{ __('Code')}}</label>
                                        <input name="vcode" id="vcode" type="text" class="form-control input-sm" width="276" value="{{ old('vcode') }}" placeholder="Code" required="" />
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="vname" class="col-form-label">{{ __('Title')}}</label>
                                        <input name="vname" id="vname" type="text" class="form-control input-sm" width="276" value="{{ old('vname') }}" placeholder="Title" required="" />
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="isactive" class="col-form-label">Is Active</label>
                                        <span class="form-control input-sm input-checkbox">
                                        <input name="isactive" id="isactive" type="checkbox" width="276" value="1" checked="">
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="vdate" class="col-form-label">{{ __('Date')}}</label> 
                                        <input name="vdate" id="vdate" type="date" class="form-control input-sm" width="276" value="{{ old('vdate') }}" placeholder="DD/MM/YYYY" required="" />
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="country" class="col-form-label">{{ __('Leave Type')}}</label>
                                        {!! Form::select('leavetypeid', $leavetypes, null,[ 'class'=>'form-control select2 form-control input-sm', 'id'=> 'leavetypeid', 'placeholder' => '--- Select ---','required'=> 'required']) !!}
                                    </div>
                                </div>
                            </div>
                            <input name="id" id="id" type="hidden" value="{{old('id')}}"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card p-3 card-border">
                <div class="card-body">
                    <table id="holidayTable" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr class="col-sm-12">
                                <th>{{ __('Code')}}</th>
                                <th>{{ __('Title')}}</th>
                                <th>{{ __('Leave Type')}}</th>
                                <th>{{ __('Date')}}</th>
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
<script src="{{ asset('js/hrpayroll/setup/holiday.js') }}"></script> 
@endpush
@endsection
