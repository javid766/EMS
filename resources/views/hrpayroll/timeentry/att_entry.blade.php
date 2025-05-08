@extends('layouts.main') 
@section('title', 'Attendance Entry')
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
                {{ Form::open(['route'=>'attendance-entry-search' , 'id' => 'attEntry']) }}
                @csrf
                <div class="card-header">
                    <div class="col-sm-5 header-title">
                        <h3>{{ __('Attendance Entry')}}</h3>
                    </div>
                    <div class="offset-sm-2 col-sm-5 header-btns">
                        <button class="btn btn-secondary" id="fillBtn"> {{ __('Fetch')}}</button>
                        <a href="#" class="btn btn-success" id ="saveBtn" >{{ __('Save')}}</a>
                        <a href="#" class="btn btn-warning" id="cancelBtnPost" >{{ __('Cancel')}}</a> 
                    </div>
                </div>
                <div class="card-body"> 
                    @csrf                                              
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label for="deptid" class="col-form-label">{{ __('Departments')}}</label>
                            <div class="form-group row">
                                <div class="col-sm-10">            
                                    {!! Form::select('deptid',$allDepts, null, ['class'=>'form-control select2', 'id'=> 'deptid']) !!}
                                 </div>
                                <!-- <div class="col-sm-2 pl-0">
                                    <input name="alldepts" id="alldepts" type="checkbox" width="276" value="-1" checked />
                                    <label for="alldepts" class="col-form-label">{{ __(' All')}}</label> 
                                </div> --> 
                            </div>                           
                        </div>

                        <div class="col-sm-2">
                            <label for="isactive" class="col-form-label">{{ __('Date')}}</label>
                            <input type="date" name="date" id ="datetofrom" class="form-control" value="{{ old('date') }}" required />   
                        </div> 

                         <div class="col-sm-2">
                            <label for="etype" class="col-form-label">{{ __('E-Type')}}</label>
                            {!! Form::select('etypeId',$allEtypes, null, ['class'=>'form-control select2', 'id'=> 'etypeid','required'=> 'required' ]) !!}  
                        </div>  
                        <div class="form-radio col-sm-3" style="bottom:-33px;">
                            <div class="radio radio-inline">
                                <label>
                                    <input type="radio" name="inout" value=0
                                    {{ old('inout') != 0 ? '' : 'checked' }}>
                                    <i class="helper"></i>In
                                </label>
                            </div>
                            <div class="radio radio-inline">
                                <label>
                                    <input type="radio" name="inout" value=1 {{ old('inout') == 1 ?'checked' : '' }}>
                                    <i class="helper"></i>Out
                                </label>
                            </div>
                            <div class="radio radio-inline">
                                <label>
                                    <input type="radio" name="inout" value=-1 {{ old('inout') == -1 ?'checked' : '' }} >
                                    <i class="helper"></i>Both
                                </label>
                            </div>
                        </div>
                         <!-- <div class="form-radio col-sm-3" style="bottom:-33px;">
                            <div class="radio radio-inline">
                                <label>
                                    <input type="radio" name="inout" value=0
                                    {{ old('inout') == 0 ? '' : 'checked' }}>
                                    <i class="helper"></i>In
                                </label>
                            </div>
                            <div class="radio radio-inline">
                                <label>
                                    <input type="radio" name="inout" value=1 {{ old('inout') == 1 ?'checked' : '' }}>
                                    <i class="helper"></i>Out
                                </label>
                            </div>
                            <div class="radio radio-inline">
                                <label>
                                    <input type="radio" name="inout" value=2 {{ old('inout') != 2 ?'checked' : '' }} >
                                    <i class="helper"></i>Both
                                </label>
                            </div>
                        </div> -->
                    </div>
                 
                </div>

            </div>
        </div>
    </div>
    @php $data = Session::get('data');
    @endphp
    @if(is_array($data) || is_object($data))
    <div class="row table_entry">
        <div class="col-sm-12">
            <div class="card card-border">
                <div class="card-body">
                    <table id="att_entry_tbl" class="table table-bordered">
                        <thead>
                            <tr>           
                                <th>{{ __('E-Code')}}</th>
                                <th>{{ __('E-Name')}}</th>
                                <th>{{ __('TimeIn')}}</th>
                                <th>{{ __('TimeOut')}}</th>
                                <th>{{ __('Remarks')}}</th>
                                <th><input type="checkbox" id ="selectall"  /></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = 1
                            @endphp
                            @if(count($data))
                            @foreach ($data as $value)
                            <input type="hidden" value="{{ $value->id }}" name="data[{{$i}}][id]" size="1" />
                            <input type="hidden" value="{{ $value->employeeid }}" name="data[{{$i}}][employeeid]" size="1" />
                            <input type="hidden" value="{{ $value->shiftid }}" name="data[{{$i}}][shiftid]" size="1" />

                            <tr>
                                <td>{{ $value->empcode }}</td>
                                <td>{{ $value->employeename }}</td>
                                <td><input type="time" class="form-control" value="@if ($value->datein != '00:00'){{ $value->datein }}@endif" name="data[{{$i}}][datein]" oninput="markCheckbox('data-{{$i}}')" /></td>
                                <td><input type="time" class="form-control" value="@if ($value->dateout != '00:00'){{ $value->dateout }}@endif" name="data[{{$i}}][dateout]" oninput="markCheckbox('data-{{$i}}')" /></td>
                                <td><input type="text" class="form-control" name="data[{{$i}}][remarks]" id="data-{{$i}}-remarks" value="{{$value->remarks}}" /></td>
                                <td><input type="checkbox" class="checkbox-entry" name="data[{{$i}}][attPost]" id="data-{{$i}}-attPost"/></td>
                            </tr> 
                            @php
                            $i++
                            @endphp
                            @endforeach
                            {{ Form::close() }}
                            @else
                            <td colspan="5" align="center">No Record Found </td>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    
        $("#datetofrom").attr("readonly", true);
        $("#deptid").attr('disabled', true);
        $("#etypeid").attr('disabled', true);
        $("#alldepts").attr('checked', true);
        $('#alldepts').change(function() {
            $("#deptid").prop('disabled', ($(this).is(":checked") == false ? false : true));
        });
        // $("#empid").attr('disabled', true);
   </script>
    @endif
</div>
<!-- push external js -->
@push('script') 
<script src="{{ asset('js/hrpayroll/timeEntry/att_entry.js') }}"></script>
<script type="text/javascript">
    $('#cancelBtnPost').click(function() {
        location.reload();
    });
</script>

<script type="text/javascript">
    function markCheckbox(dateid){
        $('#'+dateid+'-attPost').prop('checked', true);
    }
</script>

@endpush
@endsection
