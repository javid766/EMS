@extends('layouts.main') 
@section('title', 'Salary Tax Slab')
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
                <form class="forms-sample" method="POST" action="{{ route('salary-tax-slab-save') }}" >
                    <div class="card-header">
                        <div class="col-sm-5 header-title">
                            <h3>{{ __('Salary Tax Slab')}}</h3>
                        </div>
                        <div class="col-7 header-btns">
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
                                        <label for="amountfrom" class="col-form-label">{{ __('Amount From')}}</label>
                                        <input name="amountfrom" id="amountfrom" type="number" class="form-control input-sm" width="276" value="{{ old('amountfrom') }}" placeholder="0.0" required="" min="0" value="0" step=".01" />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="amountto" class="col-form-label">{{ __('Amount To')}}</label>
                                        <input name="amountto" id="amountto" type="number" class="form-control input-sm" width="276" value="{{ old('amountto') }}" placeholder="0.0" required="" />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="fixtax" class="col-form-label">{{ __('Fix Tax')}}</label>
                                        <input name="fixtax" id="fixtax" type="number" class="form-control input-sm" width="276" value="{{ old('fixtax') }}" placeholder="0.0" required="" />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="percentage" class="col-form-label">{{ __('Percentage')}}</label>
                                        <input name="percentage" id="percentage" type="number" class="form-control input-sm" width="276" value="{{ old('percentage') }}" placeholder="0.0" required="" />
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="remarks" class="col-form-label">{{ __('Remarks')}}</label>
                                        <input name="remarks" id="remarks" type="text" class="form-control input-sm" width="276" value="{{ old('remarks') }}" placeholder="Remarks" required="" />
                                    </div>
                                </div>
                                <input name="id" id="id" type="hidden" value="{{old('id')}}"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card p-3 card-border">
                <div class="card-body table-responsive-sm">
                    <table id="salaryTaxSlabTable" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Amount From')}}</th>
                                <th>{{ __('Amount To')}}</th>
                                <th>{{ __('Fix Tax')}}</th>
                                <th>{{ __('Percentage')}}</th>
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
</div>
<!-- push external js -->
@push('script') 
<script src="{{ asset('js/hrpayroll/setup/salarytaxslab.js') }}"></script> 
@endpush
@endsection
