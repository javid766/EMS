@extends('layouts.main') 
@section('title', 'Setup Reports')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush
<div class="container-fluid">
    <!-- start message area-->
    @include('include.message')
    <!-- end message area-->
    <div class="row">
        <div class="offset-md-3 col-md-6">
            <div class="card card-border">
                <div class="card-header">
                    <div class="col-md-5 header-title">
                        <h3>{{ __('Setup Reports')}}</h3>
                    </div>
                    <div class="col-7 header-btns">
                        <button class="btn btn-secondary" id ="searchBtn">{{ __('Fetch')}}</button>
                        <a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
                    </div>
                </div>
                <div class="card-body">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12 form-radio">
                            <div class="radio radio-inline">
                                <label>
                                <input type="radio" class="setupReportsRadio" name="radio" value="1" checked>
                                <i class="helper"></i>Department List
                                </label>
                            </div>
                            <br>
                            <div class="radio radio-inline">
                                <label>
                                <input type="radio" class="setupReportsRadio" name="radio" value="2">
                                <i class="helper"></i>Department Group List
                                </label>
                            </div>
                            <br>
                            <div class="radio radio-inline">
                                <label>
                                <input type="radio" class="setupReportsRadio" name="radio" value="3">
                                <i class="helper"></i>Designation List
                                </label>
                            </div>
                            <br>
                            <div class="radio radio-inline">
                                <label>
                                <input type="radio" class="setupReportsRadio" name="radio" value="4">
                                <i class="helper"></i>Holiday List
                                </label>
                            </div>
                            <br>
                            <div class="radio radio-inline">
                                <label>
                                <input type="radio" class="setupReportsRadio" name="radio" value="5">
                                <i class="helper"></i>Attendance Code List
                                </label>
                            </div>
                            <br>
                            <div class="radio radio-inline">
                                <label>
                                <input type="radio" class="setupReportsRadio" name="radio" value="6">
                                <i class="helper"></i>Salary Tax Slab
                                </label>
                            </div>
                            <br>
                        </div>
                    </div>
                    <input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="setupReportsGrid" style="display: none;">
        <div class="offset-md-3 col-md-6">
            <div class="card p-3 card-border">
                <div class="card-body">
                    <table id="setupReportsTable" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Code')}}</th>
                                <th>{{ __('Name')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="setupReportsFixTax" style="display: none;">
        <div class="offset-md-3 col-md-6">
            <div class="card p-3 card-border">
                <div class="card-body">
                    <table id="setupReportsTableFixtax" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Amount From')}}</th>
                                <th>{{ __('Amount To')}}</th>
                                <th>{{ __('Fix Tax')}}</th>
                                <th>{{ __('Percentage')}}</th>
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
 <script src="{{ asset('js/hrpayroll/setup/setup_reports.js') }}"></script>  
@endpush
@endsection
