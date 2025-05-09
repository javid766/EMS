@extends('layouts.main') 
@section('title', 'Roster Entry')
@section('content') 
<!-- push external head elements to head -->
@push('head')
<link rel="stylesheet" href="{{ asset('plugins/jquery-toast-plugin/dist/jquery.toast.min.css')}}">
<style type="text/css">
.jq-toast-wrap.top-right {
    top: 12px;
    right: 8px;
}
.radio-inline{
	margin: 0px !important;
	padding-right: 0px !important;
}
.sec-border{
	border-top: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
	padding-top: 15px;
	padding-bottom: 15px;
}
.form-group{
	margin-bottom: 0.9em;
}
.valign-m{
	vertical-align: middle !important;
}
.btn-success.disabled, .btn-success:disabled {
    background-color: #2b6ead;
    border-color: #2b6ead;
}
</style>
@endpush
<div class="container-fluid">
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<form class="forms-sample" method="POST" id="rosterEntry" action="{{url('/time-entry/roster-entry/save')}}">
	<div class="row">
		<div class="col-sm-12">		
			<div class="card card-border">				
					<div class="card-header">
						<div class="col-sm-5 header-title">
							<h3>{{ __('Roster Entry')}}</h3>
						</div>
						<div class="offset-sm-2 col-sm-5 header-btns">
							<a class="btn btn-secondary" id ="searchBtn">{{ __('Fetch')}}</a>
							<button class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
							<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body">	
						@csrf
						<div class="form-group row">
							<div class="col-sm-4">						
							    <label for="deptid" class="col-form-label">{{ __('Department')}}</label>							
								{!! Form::select('deptid', $allDepts, null,[ 'class'=>'form-control select2', 'id' => 'deptid', 'required'=> 'required']) !!}
							</div>

							<div class="col-sm-4">
								<label for="employeeid" class="col-form-label">{{ __('Employee')}}</label>
								{!! Form::select('employeeid', $empNames, null,[ 'class'=>'form-control select2', 'id'=> 'employeeid', 'placeholder'=> '--- Select Employee ---']) !!}
							</div>

							<div class="col-sm-3">
								<label for="vdate" class="col-form-label">{{ __('Month')}}</label>
								<input name="vdate" id="vdate" type="month" class="form-control input-sm" width="276"  value="" required />
							</div>																
						</div>
					</div>
				</div>
			</div>
		</div>
		@php $i = 1;
			$data = Session::get('data');
		@endphp
		@if(is_array($data) || is_object($data))
		<div class="row" id="rosterEntryGrid">
			<div class="col-md-12">
				<div class="card  card-border">
					<div class="card-body">
						<div class="table-responsive">
							<table id="rosterEntryTable" class="table">
								<thead>
									<tr>           
										<th style="min-width: 80px;">{{ __('E-Code')}}</th>
										<th style="min-width: 170px;">{{ __('E-Name')}}</th>
										<th>{{ __('01')}}</th>
										<th>{{ __('02')}}</th>
										<th>{{ __('03')}}</th>
										<th>{{ __('04')}}</th>
										<th>{{ __('05')}}</th>
										<th>{{ __('06')}}</th>
										<th>{{ __('07')}}</th>
										<th>{{ __('08')}}</th>
										<th>{{ __('09')}}</th>
										<th>{{ __('10')}}</th>
										<th>{{ __('11')}}</th>
										<th>{{ __('12')}}</th>
										<th>{{ __('13')}}</th>
										<th>{{ __('14')}}</th>
										<th>{{ __('15')}}</th>
										<th>{{ __('16')}}</th>
										<th>{{ __('17')}}</th>
										<th>{{ __('18')}}</th>
										<th>{{ __('19')}}</th>
										<th>{{ __('20')}}</th>
										<th>{{ __('21')}}</th>
										<th>{{ __('22')}}</th>
										<th>{{ __('23')}}</th>
										<th>{{ __('24')}}</th>
										<th>{{ __('25')}}</th>
										<th>{{ __('26')}}</th>
										<th>{{ __('27')}}</th>
										<th>{{ __('28')}}</th>
										<th>{{ __('29')}}</th>
										<th>{{ __('30')}}</th>
										<th>{{ __('31')}}</th>
									</tr>
								</thead>
								<tbody>
									@if(count($data))							
									@foreach ($data as $empAtt)
								    <tr>
								    	<td><span>{{ $empAtt->empcode }}</span></td>
								    	<td><span>{{ $empAtt->employeename }}</span></td>
								    	<input type="hidden" class="form-control" 
								    	 	name="data[{{$i}}][id]"  value="{{ $empAtt->id }}">
								    	<input type="hidden" class="form-control" 
								    	 	name="data[{{$i}}][employeeid]"  value="{{ $empAtt->employeeid }}">
								    	<td><input type="text" class="form-control" id="d01-{{$i}}" oninput="validateRosterShifts('d01-{{$i}}')" name="data[{{$i}}][d01]"  value="{{ $empAtt->d01 }}" maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d02-{{$i}}" oninput="validateRosterShifts('d02-{{$i}}')" name="data[{{$i}}][d02]"  value="{{ $empAtt->d02 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d03-{{$i}}" oninput="validateRosterShifts('d03-{{$i}}')" name="data[{{$i}}][d03]"  value="{{ $empAtt->d03 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d04-{{$i}}" oninput="validateRosterShifts('d04-{{$i}}')" name="data[{{$i}}][d04]"  value="{{ $empAtt->d04 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d05-{{$i}}" oninput="validateRosterShifts('d05-{{$i}}')" name="data[{{$i}}][d05]"  value="{{ $empAtt->d05 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d06-{{$i}}" oninput="validateRosterShifts('d06-{{$i}}')" name="data[{{$i}}][d06]"  value="{{ $empAtt->d06 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d07-{{$i}}" oninput="validateRosterShifts('d07-{{$i}}')" name="data[{{$i}}][d07]"  value="{{ $empAtt->d07 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d08-{{$i}}" oninput="validateRosterShifts('d08-{{$i}}')" name="data[{{$i}}][d08]"  value="{{ $empAtt->d08 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d09-{{$i}}" oninput="validateRosterShifts('d09-{{$i}}')" name="data[{{$i}}][d09]"  value="{{ $empAtt->d09 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d10-{{$i}}" oninput="validateRosterShifts('d10-{{$i}}')" name="data[{{$i}}][d10]"  value="{{ $empAtt->d10 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d11-{{$i}}" oninput="validateRosterShifts('d11-{{$i}}')" name="data[{{$i}}][d11]"  value="{{ $empAtt->d11 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d12-{{$i}}" oninput="validateRosterShifts('d12-{{$i}}')" name="data[{{$i}}][d12]"  value="{{ $empAtt->d12 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d13-{{$i}}" oninput="validateRosterShifts('d13-{{$i}}')" name="data[{{$i}}][d13]"  value="{{ $empAtt->d13 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d14-{{$i}}" oninput="validateRosterShifts('d14-{{$i}}')" name="data[{{$i}}][d14]"  value="{{ $empAtt->d14 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d15-{{$i}}" oninput="validateRosterShifts('d15-{{$i}}')" name="data[{{$i}}][d15]"  value="{{ $empAtt->d15 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d16-{{$i}}" oninput="validateRosterShifts('d16-{{$i}}')" name="data[{{$i}}][d16]"  value="{{ $empAtt->d16 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d17-{{$i}}" oninput="validateRosterShifts('d17-{{$i}}')" name="data[{{$i}}][d17]"  value="{{ $empAtt->d17 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d18-{{$i}}" oninput="validateRosterShifts('d18-{{$i}}')" name="data[{{$i}}][d18]"  value="{{ $empAtt->d18 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d19-{{$i}}" oninput="validateRosterShifts('d19-{{$i}}')" name="data[{{$i}}][d19]"  value="{{ $empAtt->d19 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d20-{{$i}}" oninput="validateRosterShifts('d20-{{$i}}')" name="data[{{$i}}][d20]"  value="{{ $empAtt->d20 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d21-{{$i}}" oninput="validateRosterShifts('d21-{{$i}}')" name="data[{{$i}}][d21]"  value="{{ $empAtt->d21 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d22-{{$i}}" oninput="validateRosterShifts('d22-{{$i}}')" name="data[{{$i}}][d22]"  value="{{ $empAtt->d22 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d23-{{$i}}" oninput="validateRosterShifts('d23-{{$i}}')" name="data[{{$i}}][d23]"  value="{{ $empAtt->d23 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d24-{{$i}}" oninput="validateRosterShifts('d24-{{$i}}')" name="data[{{$i}}][d24]"  value="{{ $empAtt->d24 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d25-{{$i}}" oninput="validateRosterShifts('d25-{{$i}}')" name="data[{{$i}}][d25]"  value="{{ $empAtt->d25 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d26-{{$i}}" oninput="validateRosterShifts('d26-{{$i}}')" name="data[{{$i}}][d26]"  value="{{ $empAtt->d26 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d27-{{$i}}" oninput="validateRosterShifts('d27-{{$i}}')" name="data[{{$i}}][d27]"  value="{{ $empAtt->d27 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d28-{{$i}}" oninput="validateRosterShifts('d28-{{$i}}')" name="data[{{$i}}][d28]"  value="{{ $empAtt->d28 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d29-{{$i}}" oninput="validateRosterShifts('d29-{{$i}}')" name="data[{{$i}}][d29]"  value="{{ $empAtt->d29 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d30-{{$i}}" oninput="validateRosterShifts('d30-{{$i}}')" name="data[{{$i}}][d30]"  value="{{ $empAtt->d30 }}"maxlength="1"></td>
								    	<td><input type="text" class="form-control" id="d31-{{$i}}" oninput="validateRosterShifts('d31-{{$i}}')" name="data[{{$i}}][d31]"  value="{{ $empAtt->d31 }}"maxlength="1"></td>						  
								    </tr>
									@php $i++ @endphp
									@endforeach	
									@else
		                            <td colspan="31" align="center">No Record Found </td>
		                            @endif																	
								</tbody>
							</table>
						</div>
						</div>
					</div>
				</div>
			</div>	
			
			@endif		
	</form>
</div>
<!-- push external js -->
@push('script') 
<script type="text/javascript">
	 function validateRosterShifts(idname,errid){
	 	var col = idname.split('-')[0];
		var row = idname.split('-')[1];
		col = col.replace("d", "");
		var rosterentry =  document.getElementById(idname).value ;
		$.ajax({
			url: 'roster-entry/validate-roster-shift/',
			data: {'rshift':rosterentry},
			type: 'get',
			dataType:"JSON",
			success:function(data){
				if (data == 0) {
					    'use strict';
					    resetToastPosition();
					    $.toast({
					      heading: 'Error',
					      text: 'Wrong Roster Shift Entry at row '+row+' and column '+ col,
					      showHideTransition: 'slide',
					      icon: 'error',
					      loaderBg: '#f2a654',
					      position: 'top-right'
					    })
					    var element = document.getElementById(idname);
        				element.style.borderColor = "red";
				}
				else{
					var element = document.getElementById(idname);
        			element.style.borderColor = "#d2cece";
					
				}

			}

		});
	}
</script>
<script src="{{ asset('plugins/jquery-toast-plugin/dist/jquery.toast.min.js')}}"></script>
<script src="{{ asset('js/hrpayroll/timeEntry/roster_entry.js') }}"></script> 
<script src="{{ asset('js/alerts.js')}}"></script>
@endpush
@endsection
