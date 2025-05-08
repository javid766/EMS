@extends('layouts.search') 
@section('title', 'Company Search')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush
<div class="container-fluid">
   <!-- start message area-->
   @include('include.message')
   <!-- end message area-->
   <div class="row search_company">
      <div class="col-12">
         <div class="card card-border">
            <div class="card-header">
               <div class="col-5 header-title">
                  <h3>{{ __('Company Search')}}</h3>
               </div>
            </div>
            <div class="card-body">
               <table id="companyTable" class="table table-bordered table-striped table-hover">
                  <thead>
                     <tr>
                        <th>{{ __('Select')}}</th>
                        <th>{{ __('Code')}}</th>
                        <th>{{ __('Company')}}</th>
                        <th>{{ __('Currency')}}</th>
                        <th>{{ __('Country')}}</th>
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
<script src="{{ asset('js/hrpayroll/setup/company.js') }}"></script> 
@endpush
@endsection