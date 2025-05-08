@extends('layouts.main') 
@section('title', 'Attendance Code')
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
                <form class="forms-sample" method="POST" action="{{ route('setup-attcode-save') }}" >
                    <div class="card-header">
                        <div class="col-sm-5 header-title">
                            <h3>{{ __('Attendance Code')}}</h3>
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
                                    <div class="col-sm-3">
                                        <label for="vcode" class="col-form-label">{{ __('Code')}}</label>	
                                        <input name="vcode" id="vcode" type="text" class="form-control input-sm" width="276" value="{{ old('vcode') }}" placeholder="Code" required />
                                    </div>
                                    <div class="col-lg-4 col-md-5 col-sm-5">
                                        <label for="vname" class="col-form-label">{{ __('Title')}}
                                        </label>					
                                        <input name="vname" id="vname" type="text" class="form-control input-sm" width="276" value="{{ old('vname') }}" placeholder="Title"  required/>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="country" class="col-form-label">{{ __('Attendance Group')}}</label>
                                        {!! Form::select('attgroupid', $attGroupsData, null,[ 'class'=>'form-control select2', 'id'=> 'attgroupid', 'placeholder' => '--- Select ---','required'=> 'required']) !!}
                                    </div>
                                    <div class="col-lg-1 col-md-3 col-sm-3">
                                        <label for="isactive" class="col-form-label">Is Active</label>
                                        <span class="form-control input-sm input-checkbox"><input id="isactive" type="checkbox" name="isactive" value="1" checked></span>
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
                    <table id="attcodeTable" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Code')}}</th>
                                <th>{{ __('Title')}}</th>
                                <th>{{ __('Attendance Group')}}</th>
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
<script src="{{ asset('js/hrpayroll/setup/attcode.js') }}"></script> 
@endpush
@endsection
