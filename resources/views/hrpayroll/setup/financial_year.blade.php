@extends('layouts.main') 
@section('title', 'Financial Year')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush
<div class="container-fluid">
    <!-- start message area-->
    @include('include.message')
    <!-- end message area-->
    <div class="row">
        <div class="offset-lg-2 col-lg-8 col-sm-12">
            <div class="card card-border">
                <form class="forms-sample" method="POST" action="{{ route('setup-financial-year-save') }}" >
                    <div class="card-header">
                        <div class="col-sm-5 header-title">
                            <h3>{{ __('Financial Year')}}</h3>
                        </div>
                        <div class="col-sm-7 header-btns">
                            <button  class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
                            <a href="#" class="btn btn-warning" id="cancelBtn">{{ __('Cancel')}}</a> 
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="vcode" class="col-form-label">{{ __('Code')}}</label>
                                        <input name="vcode" id="vcode" type="text" class="form-control input-sm" width="276" value="{{ old('vcode') }}" placeholder="Code" required="" />
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="vname" class="col-form-label">{{ __('Title')}}</label>	
                                        <input name="vname" id="vname" type="text" class="form-control input-sm" width="276" value="{{ old('vname') }}" placeholder="Title"  required=""/>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="istransactional" class="col-form-label">Is Transactional</label>                                    
                                        <span class="form-control input-sm input-checkbox"><input id="istransactional" type="checkbox" name="istransactional" value="1"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="dateto" class="col-form-label">{{ __('Date To')}}</label>
                                        <input name="dateto" id="dateto" type="date" class="form-control input-sm" width="276" value="{{ old('dateto') }}"placeholder="DD/MM/YYYY" required=""/>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="datefrom" class="col-form-label">{{ __('Date From')}}</label>                                   
                                        <input name="datefrom" id="datefrom" type="date" class="form-control input-sm" width="276" value="<?= date('Y-m-01');?>"  placeholder="DD/MM/YYYY" required=""/>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="isactive" class="col-form-label">Is Active</label>		
                                        <span class="form-control input-sm input-checkbox"><input id="isactive" type="checkbox" name="isactive" value="1" checked></span>
                                    </div>
                                </div>
                            </div>
                            <input name="id" id="id" type="hidden" value="{{ old('id') }}"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="offset-lg-2 col-lg-8 col-sm-12">
            <div class="card p-3 card-border">
                <div class="card-body">
                    <table id="accountFinancialYearTable" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Code')}}</th>
                                <th>{{ __('Title')}}</th>
                                <th>{{ __('Is Transactional')}}</th>
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
<script src="{{ asset('js/hrpayroll/setup/financial_year.js') }}"></script> 
@endpush
@endsection
