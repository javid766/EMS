@extends('layouts.main') 
@section('title', 'Department Group')
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
                <form class="forms-sample" method="POST" action="{{ route('setup-dept-group-save')}} ">
                    <div class="card-header">
                        <div class="col-sm-5 header-title">
                            <h3>{{ __('Department Group')}}</h3>
                        </div>
                        <div class="col-7 header-btns">
                            <button  class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
                            <a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="vcode" class="col-form-label">{{ __('Code')}}</label>	
                                        <input name="vcode" id="vcode" type="text" class="form-control input-sm" width="276" value="{{ old('vcode') }}" placeholder="Code" required />
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="VName" class="col-form-label">{{ __('Title')}}</label>							
                                        <input name="VName" id="VName" type="text" class="form-control input-sm" width="276" value="{{ old('VName') }}" placeholder="Title" required=""/>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="isactive" class="col-form-label">{{ __('Is Active')}}</label>			
                                        <span class="form-control input-sm input-checkbox">
                                        <input name="isactive" id="isactive" type="checkbox" width="276" value="1" checked/>
                                        </span>
                                    </div>
                                </div>
                                <br>
                                <h5 class="f-18" style="border-bottom: 1px dashed #ccc">Integration – Permanent Employee </h5>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="SalariesPermanent" class="col-form-label">{{ __('Salaries')}}</label>
                                        <input name="SalariesPermanent" id="SalariesPermanent" type="number" class="form-control input-sm" width="276" value="{{ old('SalariesPermanent') }}" placeholder="Salaries" />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="AllowancePermanent" class="col-form-label">{{ __('Allowances')}}</label>
                                        <input name="AllowancePermanent" id="AllowancePermanent" type="number" class="form-control input-sm" width="276" value="{{ old('AllowancePermanent') }}" placeholder="Allowances" />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="AdvancePermanent" class="col-form-label">{{ __('Advances')}}</label>
                                        <input name="AdvancePermanent" id="AdvancePermanent" type="number" class="form-control input-sm" width="276" value="{{ old('AdvancePermanent') }}" placeholder="Advances" />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="LoanPermanent" class="col-form-label">{{ __('Loan')}}</label>
                                        <input name="LoanPermanent" id="LoanPermanent" type="number" class="form-control input-sm" width="276" value="{{ old('LoanPermanent') }}" placeholder="LoanPermanent"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="OverTimePermanent" class="col-form-label">{{ __('Over Time')}}</label>
                                        <input name="OverTimePermanent" id="OverTimePermanent" type="number" class="form-control input-sm" width="276" value="{{ old('OverTimePermanent') }}" placeholder="Over Time"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TaxPermanent" class="col-form-label">{{ __('Withholding Tax')}}</label>
                                        <input name="TaxPermanent" id="TaxPermanent" type="number" class="form-control input-sm" width="276" value="{{ old('TaxPermanent') }}" placeholder="Withholding Tax"  />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="OtherIncomePermanent" class="col-form-label">{{ __('Other Income')}}</label>
                                        <input name="OtherIncomePermanent" id="OtherIncomePermanent" type="number" class="form-control input-sm" width="276" value="{{ old('OtherIncomePermanent') }}" placeholder="Other Income"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="SalaryPayablePermanent" class="col-form-label">{{ __('Salaries Payable')}}</label>
                                        <input name="SalaryPayablePermanent" id="SalaryPayablePermanent" type="number" class="form-control input-sm" width="276" value="{{ old('SalaryPayablePermanent') }}" placeholder="Salaries Payable"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="EmployeePFPermanent" class="col-form-label">{{ __('Employee PF')}}</label>
                                        <input name="EmployeePFPermanent" id="EmployeePFPermanent" type="number" class="form-control input-sm" width="276" value="{{ old('EmployeePFPermanent') }}" placeholder="Employee PF"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="EmployerPFPermanent" class="col-form-label">{{ __('Employer PF')}}</label>
                                        <input name="EmployerPFPermanent" id="EmployerPFPermanent" type="number" class="form-control input-sm" width="276" value="{{ old('EmployerPFPermanent') }}" placeholder="Employer PF"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="EmployeeEOBIPermanent" class="col-form-label">{{ __('Employee EOBI')}}</label>
                                        <input name="EmployeeEOBIPermanent" id="EmployeeEOBIPermanent" type="number" class="form-control input-sm" width="276" value="{{ old('EmployeeEOBIPermanent') }}" placeholder="Over Time"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="EmployerEOBIPermanent" class="col-form-label">{{ __('Employer EOBI')}}</label>
                                        <input name="EmployerEOBIPermanent" id="EmployerEOBIPermanent" type="number" class="form-control input-sm" width="276" value="{{ old('EmployerEOBIPermanent') }}" placeholder="Employer EOBI"  />
                                    </div>
                                </div>
                                <br>
                                <h5 class="f-18" style="border-bottom: 1px dashed #ccc">Integration – Contractual Employee </h5>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="SalariesContract" class="col-form-label">{{ __('Salaries')}}</label>
                                        <input name="SalariesContract" id="SalariesContract" type="number" class="form-control input-sm" width="276" value="{{ old('SalariesContract') }}" placeholder="Salaries"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="AllowanceContract" class="col-form-label">{{ __('Allowances')}}</label>
                                        <input name="AllowanceContract" id="AllowanceContract" type="number" class="form-control input-sm" width="276" value="{{ old('AllowanceContract') }}" placeholder="Allowances"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="AdvanceContract" class="col-form-label">{{ __('Advances')}}</label>
                                        <input name="AdvanceContract" id="AdvanceContract" type="number" class="form-control input-sm" width="276" value="{{ old('AdvanceContract') }}" placeholder="Advances"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="LoanContract" class="col-form-label">{{ __('Loan')}}</label>
                                        <input name="LoanContract" id="LoanContract" type="number" class="form-control input-sm" width="276" value="{{ old('LoanContract') }}" placeholder="Loan"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="OverTimeContract" class="col-form-label">{{ __('Over Time')}}</label>
                                        <input name="OverTimeContract" id="OverTimeContract" type="number" class="form-control input-sm" width="276" value="{{ old('OverTimeContract') }}" placeholder="Over Time"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="TaxContract" class="col-form-label">{{ __('Withholding Tax')}}</label>
                                        <input name="TaxContract" id="TaxContract" type="number" class="form-control input-sm" width="276" value="{{ old('TaxContract') }}" placeholder="Withholding Tax"  />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2">
                                        <label for="OtherIncomeContract" class="col-form-label">{{ __('Other Income')}}</label>
                                        <input name="OtherIncomeContract" id="OtherIncomeContract" type="number" class="form-control input-sm" width="276" value="{{ old('OtherIncomeContract') }}" placeholder="Other Income"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="SalaryPayableContract" class="col-form-label">{{ __('Salaries Payable')}}</label>
                                        <input name="SalaryPayableContract" id="SalaryPayableContract" type="number" class="form-control input-sm" width="276" value="{{ old('SalaryPayableContract') }}" placeholder="Salaries Payable"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="EmployeePFContract" class="col-form-label">{{ __('Employee PF')}}</label>
                                        <input name="EmployeePFContract" id="EmployeePFContract" type="number" class="form-control input-sm" width="276" value="{{ old('EmployeePFContract') }}" placeholder="Employee PF"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="EmployerPFContract" class="col-form-label">{{ __('Employer PF')}}</label>
                                        <input name="EmployerPFContract" id="EmployerPFContract" type="number" class="form-control input-sm" width="276" value="{{ old('EmployerPFContract') }}" placeholder="Employer PF"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="EmployeeEOBIContract" class="col-form-label">{{ __('Employee EOBI')}}</label>
                                        <input name="EmployeeEOBIContract" id="EmployeeEOBIContract" type="number" class="form-control input-sm" width="276" value="{{ old('EmployeeEOBIContract') }}" placeholder="Over Time"  />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="EmployerEOBIContract" class="col-form-label">{{ __('Employer EOBI')}}</label>
                                        <input name="EmployerEOBIContract" id="EmployerEOBIContract" type="number" class="form-control input-sm" width="276" value="{{ old('EmployerEOBIContract') }}" placeholder="Employer EOBI"  />
                                    </div>
                                </div>
                                <p style="color: #f52712; margin-top: 20px;"><b>Note: </b>Integration financial effect from salaries to Account.
                                </p>
                            </div>
                            <input name="id" id="id" type="hidden" value="{{ old('id') }}"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card p-3 card-border">
                <div class="card-body  table-responsive">
                    <table id="depGroupTable" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Code')}}</th>
                                <th>{{ __('Title')}}</th>
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
<script src="{{ asset('js/hrpayroll/setup/dep_group.js') }}"></script> 
@endpush
@endsection
