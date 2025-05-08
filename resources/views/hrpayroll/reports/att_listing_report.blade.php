@extends('layouts.main') 
@section('title', 'Daily Attendance Report')
@section('content')
<!-- push external head elements to head -->
@push('head')
<style type="text/css">
.radio-inline{
	margin: 0px !important;
	padding-right: 0px !important;
}
.sec-border{
	border-top: 1px dashed #ccc;
	border-bottom: 1px dashed #ccc;
    padding: 14px 0px 4px 0px;
    margin: 10px 0px 16px 0px !important;
}
</style>
@endpush
<div class="container-fluid">
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<div class="row">
		<div class="col-md-12">		
			<div class="card card-border">
				<form class="forms-sample" method="POST" action="{{url('#')}}" >
					<div class="card-header">
						<div class="col-5 header-title">
							<h3>{{ __('Daily Attendance Report')}}</h3>
						</div>
						<div class="offset-md-2 col-md-5 header-btns">
							<a  id="fillBtn" class="btn btn-secondary" href="#">{{ __('Fetch')}}</a>
							<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body">	
						@csrf
						<div class="row">
							<div class="col-md-12">
								<div class="form-group row">
									<!--<label for="locationid" class="col-md-1 col-form-label">{{ __('Location')}}</label>
									<div class="col-md-4">
										{!! Form::select('locationid',$locations, null, ['class'=>'form-control select2', 'id'=> 'locationid', 'placeholder'=> '--- Select Location ---']) !!}
									</div>-->

									<label for="desgid" class="col-md-1 col-form-label">{{ __('Department')}}</label>
									<div class="col-md-5">
										{!! Form::select('deptid[]', $departments, null,[ 'class'=>'form-control select2', 'multiple' => 'multiple', 'id' => 'deptid']) !!}
									</div>
									<label for="employeeid" class="col-md-1 col-form-label text-right">{{ __('Employee')}}</label>
									<div class="col-md-4">
										{!! Form::select('employeeid',$empNames, null, ['class'=>'form-control select2', 'id'=> 'employeeid', 'placeholder'=> '--- Select Employee ---']) !!}
									</div>
							</div>						

								<div class="form-group row">									

									<label for="etypeid" class="col-md-1 col-form-label">{{ __('E-Type')}}</label>
									<div class="col-md-2">
										{!! Form::select('etypeid',$eTypes, null, ['class'=>'form-control select2', 'id'=> 'etypeid']) !!}
									</div>

									<label for="vdate" class="col-md-1 col-form-label text-right">{{ __('Date')}}</label>
									<div class="col-md-2">
										<input name="vdate" id="vdate" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" />
									</div>
								</div>
							</div>
						</div>
						<div class="form-group sec-border">
							<div class="form-radio row">
								<div class="radio radio-inline col-md-2">
									<label>
										<input type="radio" name="attfilter" checked="checked" value="unpostedAtt">
										<i class="helper"></i>Unposted Attendance
									</label>
								</div>
								<div class="radio radio-inline col-md-2">
									<label>
										<input type="radio" name="attfilter" value="postedAtt">
										<i class="helper"></i>Posted Attendance
									</label>
								</div>

								<div class="radio radio-inline col-md-3">
									<label>
										<input type="radio" name="attfilter" value="unpostedAttSummary">
										<i class="helper"></i>Unposted Attendance Summary
									</label>
								</div>
								<div class="radio radio-inline col-md-3">
									<label>
										<input type="radio" name="attfilter" value="postedAttSummary">
										<i class="helper"></i>Posted Attendance Summary
									</label>
								</div>
								<div class="radio radio-inline col-md-2">
									<label>
										<input type="radio" name="attfilter" value="absenteeList">
										<i class="helper"></i>Absentee List
									</label>
								</div>
								
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="row"  id="attlistingpostedtable" style="display:none;">
		<div class="col-md-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="postedreporttable" class="table table-responsive-lg table-striped">
						<thead>
							<tr> 
								<th>Emp Code</th>
								<th>Employee</th>
								<th>Department</th> 
								<th>Designation</th> 
								<th>Start Time</th> 
								<th>Date From</th>
								<th>Date To</th>  
								<th>Total Time</th> 
								<th>Remarks</th>     
								<!-- <th>AttCode</th> 
								<th>AttName</th>  -->
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="attlistingupostedtable" style="display:none;">
		<div class="col-md-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="upostedreporttable" class="table table-responsive-lg table-striped">
						<thead>
							<tr>
								<th>Emp Code</th>
								<th>Employee</th>
								<th>Department</th> 
								<th>Designation</th> 
								<th>Time In</th>
								<th>Time Out</th>  
								<th>Remarks</th> 
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="row" id="attunpostedAttSummarytable" style="display:none;">
		<div class="col-md-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="unpostedAttSummarytable" class="table table-responsive-lg table-striped">
						<thead>
							<tr> 
								<th>Department</th>
								<th>Strength</th> 
								<th>Absentee</th> 
								<th>Present</th>
								<th>PPer</th>  
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="row" id="attpostedAttSummarytable" style="display:none;">
		<div class="col-md-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="postedAttSummarytable" class="table table-responsive-lg table-striped">
						<thead>
							<tr> 
								<th>Department</th>
								<th>Strength</th> 
								<th>Absentee</th> 
								<th>Present</th>
								<th>PPer</th>  
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="row" id="attAbsenteeListTable" style="display:none;">
		<div class="col-md-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="absenteeListTable" class="table table-responsive-lg table-striped">
						<thead>
							<tr> 
								<th>Emp Code</th>
								<th>Employee</th>
								<th>Department</th>
								<th>Designation</th> 
								<th>Remarks</th> 
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
<script src="{{ asset('js/hrpayroll/reports/att_listing_report.js') }}"></script>
@endpush
@endsection
