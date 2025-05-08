@extends('layouts.search') 
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush

<div class="container-fluid">
    <!-- start message area-->
    @include('include.message')
    <!-- end message area-->
<div class="row">
    <div class="col-md-12">
        <div class="card p-3">
            <div class="card-body">
                <table id="searchEmployeeInfo_table" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr> 
                            <th>{{ __('Select')}}</th>
                            <th>{{ __('Emp Code')}}</th>
                            <th>{{ __('Name')}}</th> 
                            <th>{{ __('Father Name')}}</th>  
                            <th>{{ __('National Id')}}</th>
                            <th>{{ __('Department')}}</th>
                            <th>{{ __('Designation')}}</th>
                            <th>{{ __('Date Of Joining')}}</th>                                                   
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
<script src="{{ asset('js/hrpayroll/employee/employee_info.js') }}"></script> 
@endpush
@endsection