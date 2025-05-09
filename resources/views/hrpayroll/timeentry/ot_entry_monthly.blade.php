@extends('layouts.main') 
@section('title', 'O/T Entry Monthly')
@section('content')
<!-- push external head elements to head -->
@push('head')
<style type="text/css">
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
</style>
@endpush
<div class="container-fluid">
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<form class="forms-sample" id="otEntryMonthly" method="POST" action="{{url('/time-entry/ot-entry-monthly/save')}}">
		<div class="row">
			<div class="col-sm-12">		
				<div class="card card-border">
					<div class="card-header">
						<div class="col-5 header-title">
							<h3>{{ __('O/T Entry Monthly')}}</h3>
							<!-- <p>Call {{Session::get('spname')}}({{0}}, {{Session::get('empid')}}, {{Session::get('vdate')}},{{Session::get('dateout')}},{{Session::get('userid')}},{{Session::get('companyid')}},{{Session::get('locationid')}});</p> -->
						</div>
						<div class="offset-sm-2 col-sm-5 header-btns">
							<button class="btn btn-secondary" id="searchBtn">{{ __('Fetch')}}</button>
							<button class="btn btn-success" id="saveBtn">{{ __('Save')}}</button>
							<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body" id="formArea">	
						@csrf
						<div class="form-group row">
						    <div class="col-sm-6">
						     <label for="desgid" class="col-form-label">{{ __('Department')}}
									   </label>		
						        <div class="row">
						        	<div class="col-sm-10">										  		
										{!! Form::select('deptid',$departments, null, ['class'=>'form-control select2', 'id'=> 'deptid']) !!}
									</div>							
									<!-- <div class="col-sm-2 pl-0">
										<input id="alldepts" class="valign-m" type="checkbox" name="alldepts" value="1" checked>
										<label for="alldepts" class="pl-10 col-form-label">All</label>
									</div>	 -->
								</div>
							</div>

							<div class="col-sm-3">
								<label for="vdate" class="col-form-label">{{ __('Date')}}</label>	
								<input name="vdate" id="vdate" type="month" class="form-control input-md" width="276" required="" value="{{old('vdate')}}" /> 
							</div>	

							<div class="col-sm-3">
							    <label for="name" class="col-form-label">{{ __('E-Type')}}</label>			{!! Form::select('etypeid',$eTypes, null, ['class'=>'form-control select2', 'id'=> 'etypeid', 'placeholder'=> '---Select---']) !!}
							</div>	
						</div>
					</div>
				</div>
			</div>
		</div>
		@php $data = Session::get('data');
		@endphp
		@if(is_array($data) || is_object($data))
			<!-- <script>
			var x = document.getElementById('formArea');
			x.style.display = 'none';
			</script> -->
		<div class="row otEntryMonthlySec">
			<div class="col-md-12">
				<div class="card card-border">
					<div class="card-body">
						<table id="otEntryMonthlyTable" class="table">
							<thead>
								<tr>           
									<th>{{ __('E-Code')}}</th>
									<th>{{ __('E-Name')}}</th>
									<th>{{ __('Overtime')}}</th>
									<th>{{ __('Remarks')}}</th>
								</tr>
							</thead>
							<tbody>
								@php $i = 1 @endphp
								@if(count($data))
								@foreach ($data as $empAtt)
								<tr>
									<td>{{ $empAtt['empcode'] }}</td>
									<td>{{ $empAtt['employeename'] }}</td>
									<input type="hidden" class="form-control" 
									name="data[{{$i}}][id]"  value="{{ $empAtt['id'] }}">
									<input type="hidden" class="form-control" 
									name="data[{{$i}}][vdate]"  value="{{ $empAtt['vdate'] }}">

									<input type="hidden" class="form-control" 
									name="data[{{$i}}][employeeid]"  value="{{ $empAtt['employeeid'] }}">
									<td><input type="text" class="form-control" 
										name="data[{{$i}}][overtime]"  value="{{ $empAtt['overtime'] }}"  maxlength="5" onkeydown="return event.charCode != 45 && event.keyCode !== 69 && event.keyCode !== 189" placeholder="0:00"></td>
									<td><input type="text" class="form-control" 
										name="data[{{$i}}][remarks]"  value="{{ $empAtt['remarks'] }}"></td>	
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
			@endif
		</form>
	</div>
	<!-- push external js -->
	@push('script') 
	<script src="{{ asset('js/hrpayroll/timeEntry/ot_entry_monthly.js') }}"></script>
	@endpush
	@endsection
