@extends('layouts.main') 
@section('title', 'Month Days Attendance')
@section('content')
<!-- push external head elements to head -->
@push('head')
<style type="text/css">
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
	<form class="forms-sample" method="POST" action="{{url('time-entry/month-days/save')}}" id='monthdaysEntry'>
	<div class="row">
		<div class="col-md-12">
			<div class="card card-border">
					<div class="card-header">
						<div class="col-md-5 header-title">
							<h3>{{ __('Month Attendance Days')}}</h3>
						</div>
						<div class="col-md-7 header-btns">
							<a href="#" class="btn btn-secondary" id ="searchBtn">{{ __('Fetch')}}</a>
							<button class="btn btn-success"  id ="saveBtn">{{ __('Save')}}</button>
							<a href="#" class="btn btn-warning" id="cancelBtn">{{ __('Cancel')}}</a> 
							<button class="btn btn-success" type="button"  id ="fillBtn" style="width: auto;" >{{ __('Fill Attendence Day')}}</button>
						</div>
					</div>
					<div class="card-body" id="formArea">	
						@csrf
						<div class="row">
							<div class="col-md-12">
								<div class="form-group row">
									<div class="col-md-4">
										<label for="deptid" class="col-form-label">{{ __('Department')}}</label>
										 {!! Form::select('deptid', $allDepts, null,[ 'class'=>'form-control select2', 'id'=> 'deptid']) !!}
									</div>

									<div class="col-md-4">
									    <label for="employeeid" class="col-form-label">{{ __('Employee')}}</label>
										  {!! Form::select('employeeid', $empNames, null,[ 'class'=>'form-control select2', 'id'=> 'employeeid', 'placeholder'=> '--- Select Employee ---']) !!}
								    </div>

									<div class="col-md-4">
										<label for="datein" class="col-form-label">{{ __('Month')}}</label>
                          					<input name="datein" id="datein" type="month" class="form-control input-md" width="276" required="" value="{{old('datein')}}" />   
									</div>																
								</div>
							</div>
						</div>					
				</div>
			</div>
		</div>
	</div>
	@php $data = Session::get('data');
	@endphp
	@if(is_array($data) || is_object($data))
	<div class="row">
		<div class="col-md-12">
			<div class="card card-border">
				<div class="card-body">
					<table id="monthDaysAttTable" class="table">
						<thead>
							<tr>           
								<th>{{ __('E-Code')}}</th>
								<th>{{ __('E-Name')}}</th>
								<th>{{ __('Att-Day')}}</th>
								<th>{{ __('Remarks')}}</th>
								 <th><input type="checkbox" id ="selectall"  /></th>
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
						    	<td><input type="number" class="form-control" id="attdays-{{$i}}" 
						    	 	name="data[{{$i}}][attdays]"  value="{{ $empAtt->attdays }}" oninput="validateMonthDays('attdayserror-{{$i}}','attdays-{{$i}}','data-{{$i}}')">
						    	 	<span class="text-red" id="attdayserror-{{$i}}"></span>
						    	 </td>
						    	 <td><input type="text" class="form-control" name="data[{{$i}}][remarks]" id="data-{{$i}}-remarks" value="{{$empAtt->remarks}}" oninput="validateMonthDays('attdayserror-{{$i}}','attdays-{{$i}}','data-{{$i}}')"/></td>
						    	 <td><input type="checkbox" class="checkbox-entry" name="data[{{$i}}][changedattdays]" id="data-{{$i}}-changedattdays"/></td>


						    </tr>
							@php $i++ @endphp
							@endforeach	
							@else
							<td colspan="3" align="center">No Record Found </td>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script>
		$("#datein").attr("readonly", true);
		$("#deptid").attr('disabled', true);
		$("#employeeid").attr('disabled', true);
		// $("#empid").attr('disabled', true);
   </script>
	@endif
	</form>
</div>
<!-- push external js -->
@push('script') 
<script type="text/javascript">
	function validateMonthDays(erridname,attidname,dateid){

		$('#'+dateid+'-changedattdays').prop('checked', true);
		
		var flag = true;
		var dateObj =  document.getElementById('datein').value ;
		var year = dateObj.split('-')[0];
		var month = dateObj.split('-')[1];
		var getMonthDays = getDaysInMonth(month, year);
		var attdays =  document.getElementById(attidname).value ;
		
		if (attdays > getMonthDays) {
		
			document.getElementById(erridname).innerHTML = 'Days can not be greater than ' + getMonthDays;
			flag = false;
		
		} else {
		
			document.getElementById(erridname).innerHTML = '';
			flag = true;
		}
		
		if (attdays <= 0) {
		
			document.getElementById(erridname).innerHTML = 'Days should be greater than 0';
			flag = false;
			$('#'+dateid+'-changedattdays').prop('checked', false);
		}
		
		if (flag == false) {
		
			document.getElementById(attidname).value = 0 ;
		}
	}

	function getDaysInMonth(month,year) {
	
		return new Date(year, month, 0).getDate();
	}

    function markCheckbox(dateid){
    	
        $('#'+dateid+'-changedattdays').prop('checked', true);
    }

</script>
<script src="{{ asset('js/hrpayroll/timeEntry/month_days_attendance.js') }}"></script> 
@endpush
@endsection
