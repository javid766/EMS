@extends('layouts.main') 
@section('title', 'Monthly Attendance Report')
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
    	padding-top: 13px;
    	padding-bottom: 5px;
    	margin-top: 15px;
	}
	.form-group{
		margin-bottom: 0.9em;
	}
	div.table-responsive>div.dataTables_wrapper>div.row>div[class^="col-"]:last-child{
        padding-right: 2px !important;
    }
    .rpt-group{
    	background-color: #4f5d83 !important;
  		color: white !important;
  		padding: 8px;
    }
    .rpt-group-content{
    	padding: 6px !important;
    	font-size: 12px !important;
    }
</style>
@endpush
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<div class="row">	
		<div class="col-sm-12">	
			<div class="card card-border">
				<form class="forms-sample" method="POST" action="{{url('#')}}" >
					<div class="card-header">
						<div class="col-5 header-title">
							<h3>{{ __('Monthly Attendance Report')}}</h3>
						</div>
						<div class="offset-sm-2 col-sm-5 header-btns">
							<a  id="fillBtn" class="btn btn-secondary" href="#">{{ __('Fetch')}}</a>
							<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body">	
						@csrf
						<div class="form-group row">
							<div class="col-sm-5">
								<label for="empcode" class="col-form-label">{{ __('Employee')}}</label>
								{!! Form::select('employeeid',$empNames, null, ['class'=>'form-control select2', 'id'=> 'employeeid', 'placeholder'=> '--- Select Employee ---']) !!}
							</div>

							<!--<div class="col-sm-4">
								<label for="locationid" class="col-form-label">{{ __('Location')}}</label>
								{!! Form::select('locationid',$locations, null, ['class'=>'form-control select2', 'id'=> 'locationid', 'placeholder'=> '--- Select Location ---']) !!}
							</div>-->

							<div class="col-sm-4">						
								<label for="name" class="col-form-label">{{ __('E-Type')}}</label>	{!! Form::select('eTypeid',$eTypes, null, ['class'=>'form-control select2', 'id'=> 'eTypeid']) !!}
							</div>			
						</div>						

						<div class="form-group row">
							<div class="col-sm-5">
								<label for="deptid" class="col-form-label">{{ __('Department')}}</label>
								{!! Form::select('deptid[]', $departments, null,[ 'class'=>'form-control select2', 'multiple' => 'multiple', 'id' => 'deptid']) !!}
							</div>	

							<div class="col-sm-2">
								<label for="name" class="col-form-label">{{ __('Date From')}}</label>
								<input name="datefrom" id="datefrom" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" value="<?= date('Y-m-01');?>" />
							</div>

							<div class="col-sm-2">
								<label for="name" class="col-form-label">{{ __('Date To')}}</label>
								<input name="dateto" id="dateto" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" />
							</div>
						</div>								
	
						<div class="form-group sec-border">
							<div class="form-radio row">
								<div class="radio radio-inline col-sm-2">
									<label>
										<input type="radio" name="attfilter" value="attcard" checked>
										<i class="helper"></i>Attendance Card
									</label>
								</div>
								<div class="radio radio-inline col-sm-2">
									<label>
										<input type="radio" name="attfilter" value="absenteelist">
										<i class="helper"></i>Absentee List
									</label>
								</div>
								<div class="radio radio-inline col-sm-2">
									<label>
										<input type="radio" name="attfilter" value="attmsummary">
										<i class="helper"></i>Attendance Summary
									</label>
								</div>
								<div class="radio radio-inline col-sm-2">
									<label>
										<input type="radio" name="attfilter" value="attlogs">
										<i class="helper"></i>Attendance Logs
									</label>
								</div>
								<div class="radio radio-inline col-sm-2">
									<label>
										<input type="radio" name="attfilter" value="leavelist">
										<i class="helper"></i>Leave List
									</label>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="row" id="attendancecardtable" style="display:none;">
		<div class="col-sm-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="attcardtable" class="table table-responsive-lg table-striped">
						<thead>
							<tr> 
								<th>Employee</th>
								<th>Date</th> 
								<th>StartTime</th> 
								<th>OverTime</th> 
								<th>TotalTime</th> 
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
	<div class="row" id="attabsenteelisttable" style="display:none;">
		<div class="col-sm-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="absenteelisttable" class="table table-responsive-lg table-striped">
						<thead>
							<tr> 
								<th>Employee</th>
								<th>Department</th> 
								<th>Designation</th>
								<th>DOJ</th>  
								<th>StartTime</th>
								<th>Absent List</th> 
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="attMonthlySummarytable" style="display:none;">
		<div class="col-sm-12">
			<div class="card p-3 card-border">
				<div class="card-body table-responsive">
					<table id="monthlySummarytable" class="table table-striped table-hover" style="width: 280% !important;">
						<thead>
							<tr> 
								<th>Employee</th>
								<th>Department</th> 
								<th>Designation</th> 
								<th>Month Days</th>
								<th>Working Days</th> 
								<th>O/T</th> 
								<th>Present Days</th>
								<th>Half Day</th> 
								<th>Gazetted Holidays</th>
								<th>Festival Holidays</th>
								<th>Weekend</th>
								<th>Annual</th>
								<th>Casual</th>
								<th>Sick</th>
								<th>ML</th>
								<th>CPL</th>
								<th>Absent Days</th>
								<th>Net Days</th>
								<th>Deduction Days</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="attendancelogstable" style="display:none;">
		<div class="col-sm-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="attlogstable" class="table table-responsive-lg table-striped">
						<thead>
							<tr> 
								<th>Employee</th> 
								<th>Date</th> 
								<th>Start Time</th>				
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
	<div class="row" id="attLeaveListtable" style="display:none;">
		<div class="col-sm-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="leavelisttable" class="table table-responsive-lg table-striped">
						<thead>
							<tr> 
								<th>Employee</th>
								<th>Department</th> 
								<th>Designation</th> 
								<th>Date From</th> 
								<th>Date To</th> 
								<th>Leave</th>
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
<!-- push external js -->
@push('script') 
<script src="{{ asset('js/hrpayroll/reports/monthly_att_report.js') }}"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/datetime-moment.js"></script>
@endpush
@endsection
