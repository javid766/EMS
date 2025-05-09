@extends('layouts.main') 
@section('title', 'Trial Card Printing')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush
<div class="container-fluid">
    <!-- start message area-->
    @include('include.message')
    <!-- end message area-->
    <div class="row"> 
    <div class="offset-sm-1 col-sm-10">     
        <div class="card card-border">
            <form class="forms-sample" method="POST" action="{{url('/setup/fixtaxslab/save')}}" >
                <div class="card-header">
                    <div class="col-sm-5 header-title">
                        <h3>{{ __('Trial Card Printing')}}</h3>
                    </div>
                    <div class="offset-sm-2 col-sm-5 header-btns">
                        <a href="#" class="btn btn-secondary" id="cancelBtn" >{{ __('Print')}}</a> 
                        <button  id="save-button" class="btn btn-success" id ="searchBtn">{{ __('Fill')}}</button>
                        <a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
                    </div>
                </div>
                <div class="card-body"> 
                    @csrf                                              
                        <div class="form-group row">
                             <label for="desgid" class="col-sm-2 col-form-label">{{ __('Departments')}}</label>
                            {!! Form::select('allDepts',$allDepts, null, ['class'=>'form-control select2 col-sm-4', 'id'=> 'desgid']) !!}

                            <div class="col-sm-4">
                                <input type="text" name="" class="form-control input-sm">
                            </div>

                            <div class="col-md-2">
                                <input class="col-md-2" name="isactive" id="isactive" type="checkbox" width="276" value="1"/>
                                <label for="isactive" class="col-form-label">{{ __('All Departments')}}</label>                               
                            </div>
                        </div>
                        <input name="id" id="id" type="hidden" value=""/>
                        <input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
                    </form>
                </div>
        </div>
    </div>
</div>
    <div class="row" style="display: none;">

        <div class="card p-3 card-border">
            <div class="card-body">
                <table id="deptTable" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>           
                            <th>{{ __('Code')}}</th>
                            <th>{{ __('Title')}}</th>
                            <th>{{ __('SrlNo')}}</th>
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
<!-- <script src="{{ asset('js/hrpayroll/setup/dept.js') }}"></script>  -->
@endpush
@endsection
