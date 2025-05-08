@extends('layouts.main') 
@section('title', 'Daily Manual O/T')
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
	<form class="forms-sample" id="otEntryDaily" method="POST" action="{{url('/time-entry/daily-manual-ot/save')}}" >
		<div class="row">
			<div class="col-sm-12">		
				<div class="card card-border">
					<div class="card-header">
						<div class="col-5 header-title">
							<h3>{{ __('Daily Manual O/T')}}</h3>
						</div>
						<div class="offset-sm-2 col-sm-5 header-btns">
							<button class="btn btn-secondary" id ="searchBtn">{{ __('Fetch')}}</button>
							<button class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
							<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body" id="formArea">	
						@csrf
						<div class="form-group row">

							<label for="deptid" class="col-sm-1 pr-0 col-form-label">{{ __('Department')}}</label>
							<div class="col-sm-3">
								{!! Form::select('deptid',$departments, null, ['class'=>'form-control select2', 'id'=> 'deptid']) !!}
							</div>

							<!-- <div class="col-sm-1">
								<input id="alldepts" class="valign-m" type="checkbox" name="alldepts" value="1" checked>
								<label for="alldepts" class="pl-10 col-form-label">All</label>

							</div> -->

							<div class="col-sm-1 pr-0 pl-0">
								<input id="otonly" class="valign-m" type="checkbox" name="otonly" value="1">
								<label for="otonly" class="pl-10 col-form-label">O/T Only</label>
							</div>

							<label for="name" class="col-sm-1 col-form-label text-right">{{ __('Date')}}</label>
							<div class="col-sm-2">
								<input name="vdate" id="vdate" type="date" class="form-control input-md" width="276" required="" value="{{old('vdate') }}" />
							</div>	

							<label for="name" class="col-sm-1 col-form-label text-right">{{ __('E-Type')}}</label>
							<div class="col-sm-2">
								{!! Form::select('etypeid',$eTypes, null, ['class'=>'form-control select2', 'id'=> 'etypeid']) !!}
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
		<div class="row otEntryDailySec">
			<div class="col-md-12">
				<div class="card card-border">
					<div class="card-body">
						<table id="otEntryDailyTable" class="table">
							<thead>
								<tr>           
									<th style="min-width: 85px;">{{ __('E-Code')}}</th>
									<th>{{ __('E-Name')}}</th>
									<th>{{ __('Date')}}</th>
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
									name="data[{{$i}}][employeeid]"  value="{{ $empAtt['employeeid'] }}">
									<td><input type="text" class="form-control" value="{{ $empAtt['filldate'] }}" name="data[{{$i}}][filldate]" readonly /></td>
									<input type="hidden" class="form-control" value="{{ $empAtt['vdate']}}" name="data[{{$i}}][vdate]"  />
									<td><input type="text" class="form-control" 
										name="data[{{$i}}][overtime]"  value="{{ $empAtt['overtime'] }}" maxlength="5" onkeydown="return event.charCode != 45 && event.keyCode !== 69 && event.keyCode !== 189" placeholder="0.00"></td>

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
	<script type="text/javascript">
	    
	    $('#cancelBtn').click(function() {
	        location.reload();
	    });

	    var isOTOnlyChecked = "{{ old('otonly') }}";
    	$('#otonly').prop('checked', isOTOnlyChecked == 1);
	    

	</script>
	<script src="{{ asset('js/hrpayroll/timeEntry/ot_entry_daily.js') }}"></script> 

	@endpush
	@endsection
