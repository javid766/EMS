@extends('layouts.main') 
@section('title', 'Leave Apply')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush


<div class="container-fluid">
    <!-- start message area-->
    @include('include.message')
    <!-- end message area-->
        <div class="card card-border">
          <form class="forms-sample" method="POST" action="{{url('time-entry/leave-entry/save')}}">
            <div class="card-header">
                <div class="col-md-5 header-title">
                    <h3>{{ __('Leave Apply')}}</h3>
                </div>
                <div class="offset-md-2 col-md-5 header-btns">
                    <button class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
                    <a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
                </div>
            </div>
            <div class="card-body">        
                    @csrf
                        <div class="form-group row">
                                <div class="col-sm-4">
                                   <label for="employeeid" class="col-form-label">{{ __(' Employee')}}</label> 
                                   {!! Form::select('employeeid', $employees, null,[ 'class'=>'form-control select2', 'id'=> 'employeeid', 'placeholder'=> '--- Select Employee ---', 'required'=> 'required']) !!}
                                </div>

                                <div class="col-sm-4">
                                     <label for="leavetypeid" class="col-form-label">{{ __('Leave Type')}}</label>                      
                                    {!! Form::select('leavetypeid', $leaveType, null,[ 'class'=>'form-control select2', 'placeholder' => '--- Select Leave Type ---','id'=> 'leavetypeid', 'required'=> 'required']) !!}
                                </div>

                                <div class="col-sm-2">
                                     <label for="datein" class="col-form-label">{{ __('Date From')}}</label>
                                    <input name="datein" id="datein" type="date" class="form-control input-sm" width="276" min='2020-01-01' max='2020-13-13' required="" />
                                </div>

                                <div class="col-sm-2">
                                   <label for="dateout" class="col-form-label">{{ __('Date To')}}</label>
                                    <input name="dateout" id="dateout" type="date" class="form-control input-sm" width="276" required=""/>
                                </div>                  
                            </div>     

                            <div class="form-group row">
                                <div class="col-sm-4">
                                  <label for="remarks" class="col-form-label">{{ __('Remarks')}}
                                  </label>         
                                   <input id="remarks" type="text" class="form-control" name="remarks" value="{{ old('remarks') }}" placeholder="Remarks" required>
                                </div>
                            </div>

                  <input type="hidden" id="id" name="id" value="{{ old('id') }}">
                 <input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
             </form>
        </div>      
    </div>

    <div class="row">
          <div class="col-sm-12">
             <div class="card p-3 card-border">
                <div class="card-body">
                   <table id="leaveEntryTable" class="table table-bordered table-striped table-hover">
                      <thead>
                         <tr>           
                            <th>{{ __('Employee')}}</th>
                            <th>{{ __('Leave Type')}}</th>
                            <th>{{ __('Date From')}}</th>
                            <th>{{ __('Date To')}}</th>
                            <th>{{ __('Status')}}</th>
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
<script src="{{ asset('js/hrpayroll/timeEntry/leave_apply.js') }}"></script> 
<script type="text/javascript">
    document.getElementById('datein').valueAsDate = new Date();
    document.getElementById('dateout').valueAsDate = new Date();
</script>
@endpush
@endsection
