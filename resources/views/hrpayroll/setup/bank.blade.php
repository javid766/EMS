@extends('layouts.main') 
@section('title', 'Salary Banks')
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
                <form class="forms-sample" method="POST" action="{{ route('setup-bank-save'') }}" >
                    <div class="card-header">
                        <div class="col-sm-5 header-title">
                            <h3>{{ __('Salary Banks')}}</h3>
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
                                    <label for="vcode" class="col-sm-2 col-form-label">{{ __('Code')}}</label>
                                    <div class="col-sm-4">
                                        <input name="vcode" id="vcode" type="text" class="form-control input-sm" width="276" value="{{ old('vcode') }}" placeholder="Code"  required="" />
                                    </div>
                                    <label for="vname" class="col-sm-2 col-form-label">{{ __('Title')}}</label>
                                    <div class="col-sm-4">
                                        <input name="vname" id="vname" type="text" class="form-control input-sm" width="276" value="{{ old('vname') }}" placeholder="Title"  required="" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="branchname" class="col-sm-2 col-form-label">{{ __('Branch Name')}}</label>
                                    <div class="col-sm-4">
                                        <input name="branchname" id="branchname" type="text" class="form-control input-sm" width="276" value="{{ old('branchname') }}" placeholder="Branch Name"  required="" />
                                    </div>
                                    <label for="accountnumber" class="col-sm-2 col-form-label">{{ __('Account No.')}}</label>
                                    <div class="col-sm-4">
                                        <input name="accountnumber" id="accountnumber" type="text" class="form-control input-sm" width="276" value="{{ old('accountnumber') }}" placeholder="Account No"  required="" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="accountmanager" class="col-sm-2 col-form-label">{{ __('Account Manger')}}</label>
                                    <div class="col-sm-4">
                                        <input name="accountmanager" id="accountmanager" type="text" class="form-control input-sm" width="276" value="{{ old('accountmanager') }}" placeholder="Account Manger"  required="" />
                                    </div>
                                    <label for="isactive" class="col-sm-2 col-form-label">{{ __('Is Active')}}</label>
                                    <div class="col-sm-4">
                                        <input class="align-checkbox" name="isactive" id="isactive" type="checkbox" width="276" value="1" checked/>
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
                <div class="card-body table-responsive-sm">
                    <table id="bankTable" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Code')}}</th>
                                <th>{{ __('Title')}}</th>
                                <th>{{ __('Branch Name')}}</th>
                                <th>{{ __('Account No.')}}</th>
                                <th>{{ __('Account Manager')}}</th>
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
 <script src="{{ asset('js/hrpayroll/setup/bank.js') }}"></script> 
@endpush
@endsection
