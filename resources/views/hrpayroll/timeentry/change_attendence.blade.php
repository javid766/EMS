@extends('layouts.main') 
@section('title', 'Change Attendence')
@section('content')
<!-- push external head elements to head -->
@push('head')
<link rel="stylesheet" href="{{ asset('plugins/jquery-toast-plugin/dist/jquery.toast.min.css')}}">
<style type="text/css">
.jq-toast-wrap.top-right {
    top: 12px;
    right: 8px;
}
</style>
@endpush
<div class="container-fluid">
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<form class="forms-sample" id="changeAtt" method="POST" action="{{url('/time-entry/change-attendence/save')}}" >
		<div class="row">
			<div class="col-sm-12">
				<div class="card card-border">
					<div class="card-header">
						<div class="col-sm-5 header-title">
							<h3>{{ __('Change Attendence')}}</h3>
						</div>
						<div class="offset-sm-2 col-sm-5 header-btns">
							<a class="btn btn-secondary" id="searchBtn">{{ __('Fetch')}}</a>
							<button  class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
							<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body" id="formArea">	
						@csrf
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group row">
									<div class="col-md-3">
										<label for="deptid" class="col-form-label">{{ __('Department')}}</label>
										{!! Form::select('deptid', $allDepts, null,[ 'class'=>'form-control select2', 'id'=> 'deptid']) !!}
									</div>
									<div class="col-sm-3">
										<label for="empid" class="col-form-label">{{ __('Employee Name')}}</label>
										{!! Form::select('empid', $empNames, null,[ 'class'=>'form-control select2', 'id'=> 'empid', 'placeholder'=> '--- Select Employee ---']) !!}
									</div>
									<div class="col-sm-3">
										<label for="vdate" class="col-form-label">{{ __('Date')}}</label>
										<input name="vdate" id="vdate" type="date" class="form-control input-md" width="276" required="" value="{{ old('vdate') }}" />
									</div>
									<div class="col-sm-3">
										<label for="etypeid" class="col-form-label">{{ __('E-Type')}}</label>
										{!! Form::select('etypeid', $eTypes, null,[ 'class'=>'form-control select2', 'id'=> 'etypeid', 'required'=> 'required']) !!}
									</div>
								</div>
							</div>
							<input name="id" id="id" type="hidden" value=""/>
						</div>
					</div>
				</div>
			</div>
		</div>
		@php $data = Session::get('data');
		@endphp
		@if(is_array($data) || is_object($data))
		<div class="row changeAttendanceSec">
			<div class="col-md-12">
				<div class="card card-border">
					<div class="card-body">
						<table id="changeAttendanceTable" class="table">
							<thead>
								<tr>           
									<th style="min-width: 85px;">{{ __('E-Code')}}</th>
									<th>{{ __('E-Name')}}</th>
									<th>{{ __('TimeIn')}}</th>
									<th>{{ __('TimeOut')}}</th>
									<th>{{ __('Remarks')}}</th>
								</tr>
							</thead>
							<tbody>
								@php $i = 1 @endphp
								@if(count($data))
								@foreach ($data as $empAtt)
								<tr>
									<td>{{ $empAtt->empcode }}</td>
									<td>{{ $empAtt->employeename }}</td>
									<input type="hidden" class="form-control" 
									name="data[{{$i}}][id]"  value="{{ $empAtt->id }}">
									<input type="hidden" class="form-control" 
									name="data[{{$i}}][employeeid]"  value="{{ $empAtt->employeeid }}">
									<input type="hidden" class="form-control" 
									name="data[{{$i}}][date]"  value="{{ $empAtt->vdate }}">
									<td><input type="time" class="form-control" value="@if ($empAtt->datein != '00:00'){{ $empAtt->datein }}@endif" name="data[{{$i}}][datein]" /></td>
                           			<td><input type="time" class="form-control" value="@if ($empAtt->dateout != '00:00'){{ $empAtt->dateout }}@endif" name="data[{{$i}}][dateout]" /></td>
                           			<td><input type="text" class="form-control" name="data[{{$i}}][remarks]" id="data-{{$i}}-remarks" value="{{ $empAtt->remarks }}"/></td>
									</tr>
									@php $i++ @endphp
									@endforeach	
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
				$("#vdate").attr("readonly", true);
				$('#deptid').val('{{Session::get("q_deptid")}}').change();
				$("#deptid").attr('disabled', true);
				$("#etypeid").attr('disabled', true);
				$("#empid").attr('disabled', true);
		   </script>
			@endif
	</form>
</div>
<!-- push external js -->
@push('script') 
<script src="{{ asset('js/hrpayroll/timeEntry/change_attendence.js') }}"></script> 
<script src="{{ asset('js/alerts.js')}}"></script>
<script src="{{ asset('plugins/jquery-toast-plugin/dist/jquery.toast.min.js')}}"></script>
@endpush
@endsection
