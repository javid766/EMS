@extends('layouts.main') 
@section('title', 'Official Visit Entry')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush


<div class="container-fluid">
    <!-- start message area-->
    @include('include.message')
    <!-- end message area-->
        <div class="card card-border">
          <form class="forms-sample" method="POST" action="official-visit-entry/save" >
            <div class="card-header">
                <div class="col-5 header-title">
                    <h3>{{ __('Official Visit Entry')}}</h3>
                </div>
                <div class="offset-2 col-5 header-btns">
                    <button  id="saveBtn" class="btn btn-success">{{ __('Save')}}</button>
                    <a type="submit" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
                </div>
            </div>
            <div class="card-body">
                    @csrf
                    <div class="form-group row">                               
                                <div class="col-sm-3">
                                    <label for="appno" class="col-form-label">{{ __('App #')}}</label>
                                    <input name="appno" id="appno" type="number" class="form-control input-sm" width="276" value="{{ old('appno') }}" />
                                </div>

                                <div class="col-sm-3">
                                  <label for="empid" class="col-form-label">{{ __(' Employee')}}</label>
                                  {!! Form::select('empid', $employees, null,[ 'class'=>'form-control select2', 'id'=> 'empid', 'placeholder'=> '--- Select Employee ---', 'required'=> 'required']) !!}
                               </div>    

                                <div class="col-sm-3">
                                  <label for="vdatefrom" class="col-form-label">{{ __('Date From')}}</label>
                                  <input name="vdatefrom" id="vdatefrom" type="datetime-local" class="form-control input-sm" width="276" required="" />
                                </div>
                          
                                <div class="col-sm-3">
                                     <label for="vdateto" class="col-form-label">{{ __('Date To')}}</label>
                                    <input name="vdateto" id="vdateto" type="datetime-local" class="form-control input-sm" width="276" required=""  />
                                </div>

                        </div>

                        <div class="form-group row">                   
                            <div class="col-sm-6">
                                <label for="remarks" class="col-form-label">{{ __('Remarks')}}</label>
                               <input id="remarks" type="text" class="form-control" name="remarks" value="{{ old('remarks') }}" placeholder="Remarks" required>
                           </div>
                         </div>              
        

                        <input name="id" id="id" type="hidden" value=""/>
                        <input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
            </div>
    </form>

</div>
  <div class="row">
      <div class="col-sm-12">
         <div class="card p-3 card-border">
            <div class="card-body">
               <table id="officialVisitEntryTable" class="table table-bordered table-striped table-hover">
                  <thead>
                     <tr>           
                        <th>{{ __('App #')}}</th>
                        <th>{{ __('Employee')}}</th>
                        <th>{{ __('Time From')}}</th>
                        <th>{{ __('Time From')}}</th>
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
    <script src="{{ asset('js/hrpayroll/timeEntry/official_visit_entry.js') }}"></script> 
@endpush
@endsection
