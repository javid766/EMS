@extends('layouts.main') 
@section('title', 'Monthly Salary Report')
@section('content')
<!-- push external head elements to head -->
@push('head')
<link rel="stylesheet" href="{{ asset('plugins/jquery-toast-plugin/dist/jquery.toast.min.css')}}">
<style type="text/css">
	.radio-inline{
		margin: 0px !important;
		padding-right: 0px !important;
	}
	.sec-border{
		border-top: 1px dashed #ccc;
		border-bottom: 1px dashed #ccc;
		padding-top: 15px;
		padding-bottom: 5px;
	}
	div.table-responsive>div.dataTables_wrapper>div.row>div[class^="col-"]:last-child{
        padding-right: 2px !important;
    }
    .dept-group {
	    background-color: #4f5d83 !important;
	    color: white !important;
	}
</style>
@endpush
<div class="container-fluid">
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<div class="row">
		<div class="col-sm-12">		
			<div class="card card-border">
				<form class="forms-sample" method="POST" action="{{url('#')}}" >
					<div class="card-header">
						<div class="col-5 header-title">
							<h3>{{ __('Monthly Salary Report')}}</h3>
						</div>
						<div class="offset-sm-2 col-sm-5 header-btns">
							<a  id="fillBtn" class="btn btn-secondary" href="#">{{ __('Fetch')}}</a>
							<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body">	
						<input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group row">
									<label for="empcode" class="col-sm-1 col-form-label">{{ __('Employee')}}</label>
									<div class="col-sm-3">
										{!! Form::select('employeeid',$empNames, null, ['class'=>'form-control select2', 'id'=> 'employeeid', 'placeholder'=> '--- Select Employee ---']) !!}
									</div>
									
									<!-- <label for="locationid" class="col-sm-1 col-form-label">{{ __('Location')}}</label>
									<div class="col-sm-3">
										{!! Form::select('locationid',$locations, null, ['class'=>'form-control select2', 'id'=> 'locationid', 'placeholder'=> '--- Select ---']) !!}
									</div> -->

									<label for="name" class="col-sm-1 col-form-label">{{ __('E-Type')}}</label>
									<div class="col-sm-3">
										{!! Form::select('eTypeid',$eTypes, null, ['class'=>'form-control select2', 'id'=> 'eTypeid', 'placeholder'=> '--- Select ---']) !!}
									</div>

								</div>						

								<div class="form-group row">						
									
									<label for="name" class="col-sm-1 col-form-label">{{ __('Month')}}</label>
									<div class="col-sm-3">
										<input name="datein" id="datein" type="month" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" />
									</div>
									
									<label for="deptid" class="col-sm-1 col-form-label pr-0">{{ __('Department')}}</label>
									<div class="col-sm-3">
										{!! Form::select('deptid[]', $departments, null,[ 'class'=>'form-control select2', 'multiple' => 'multiple', 'id' => 'deptid']) !!}
									</div>

								</div>
							</div>
						</div>
						<div class="form-group sec-border">
							<div class="form-radio row">
								<div class="radio radio-inline col-sm-3">
									<label>
										<input type="radio" name="attfilter" value="salsheetcash" checked="checked">
										<i class="helper"></i>Salary Sheet Cash
									</label>
								</div>
								<div class="radio radio-inline col-sm-3">
									<label>
										<input type="radio" name="attfilter" value="salsheetcheque">
										<i class="helper"></i>Salary Sheet Cheque
									</label>
								</div>
								
								<div class="radio radio-inline col-sm-3">
									<label>
										<input type="radio" name="attfilter" value="salsheetcomplete">
										<i class="helper"></i>Salary Sheet Complete
									</label>
								</div>
								<div class="radio radio-inline col-sm-3">
									<label>
										<input type="radio" name="attfilter" value="finalsettlement">
										<i class="helper"></i>Final Settlement
									</label>
								</div>
								<div class="radio radio-inline col-sm-3">
									<label>
										<input type="radio" name="attfilter" value="salarysummarycash">
										<i class="helper"></i>Salary Summary Cash
									</label>
								</div>
								<div class="radio radio-inline col-sm-3">
									<label>
										<input type="radio" name="attfilter" value="salarysummarycheque">
										<i class="helper"></i>Salary Summary Cheque
									</label>
								</div>
								<div class="radio radio-inline col-sm-3">
									<label>
										<input type="radio" name="attfilter" value="summarycomplete">
										<i class="helper"></i>Summary Complete
									</label>
								</div>
								<div class="radio radio-inline col-sm-3">
									<label>
										<input type="radio" name="attfilter" value="finalsettlementsummary">
										<i class="helper"></i>Final Settlement Summary
									</label>
								</div>
								<div class="radio radio-inline col-sm-3">
									<label>
										<input type="radio" name="attfilter" value="salaryslippdf">
										<i class="helper"></i>Salary Slip (PDF)
									</label>
								</div>
								<div class="radio radio-inline col-sm-3">
									<label>
										<input type="radio" name="attfilter" value="empsalaryhistory">
										<i class="helper"></i>Employee Salary History
									</label>
								</div>
							
								<div class="radio radio-inline col-sm-3">
									<label>
										<input type="radio" name="attfilter" value="advancedeductionsheet">
										<i class="helper"></i>Advance/Deduction Sheet
									</label>
								</div>
								<div class="radio radio-inline col-sm-3">
									<label>
										<input type="radio" name="attfilter" value="advancedeductionsummary">
										<i class="helper"></i>Advance/Deduction Summary
									</label>
								</div>
							</div>
						</div>

						<!-- <div class="form-group row">
							<label for="reportheading" class="col-sm-2 col-form-label">{{ __('Report Heading')}}</label>
							<div class="col-sm-7">
								<input name="reportheading" id="reportheading" type="text" class="form-control input-sm" width="276" placeholder="Report Heading"  />
							</div>
						</div> -->
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="row"  id="attMonthlySaltable" style="display:none;">
		<div class="col-sm-12">
			<div class="card p-3 card-border">
				<div class="card-body table-responsive">
					<table id="monthlySaltable" class="table table-striped table-hover" style="width:230%!important;">
						<thead>
							<tr> 
								<th>E-Code</th>  
								<th>Employee</th>  
								<th>Department</th>  
								<th>Designation</th>  
								<th>Basic Salary</th> 
								<th>Working Days</th>  
								<th>Salary Days</th>
								<th>O/T </th> 
								<th>Allowance</th>  
								<th>Gross Salary</th> 							
								<th>Income Tax</th>
								<th>Advance</th>  
								<th>Loan</th>  
								<th>Deduction</th> 
								<th>Net Salary</th>  
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>


	<div class="row"  id="attMonthlySalSumtable" style="display:none;">
		<div class="col-sm-12">
			<div class="card p-3 card-border">
				<div class="card-body table-responsive">
					<table id="monthlySalSumtable" class="table table-striped table-hover" style="width:150%!important;">
						<thead>
							<tr> 
								<th>Department</th>    
								<th>Basic Salary</th>  
								<th>Gross Salary</th>
								<th>Income Tax</th>						
								<th>Advance</th>  
								<th>Loan</th>  
								<th>Allowance</th>  
								<th>Deduction</th> 														
								<th>Overtimers</th> 
								<th>Net Salary</th>  
								<th>Net Payable</th>  
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="row" id="attEmpSalHistorytable" style="display:none;">
		<div class="col-sm-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="empSalHistorytable" class="table table-striped table-hover">
						<thead>
							<tr> 
								<th>Employee</th> 							
								<th>Month</th>  
								<th>Basic Salary</th> 
								<th>Days</th>
								<th>Gross Salary</th>
								<th>Advance</th>  
								<th>Loan</th>
								<th>Net Pay</th>  
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="row" id="attAdvanceDeductionSheettable" style="display:none;">
		<div class="col-sm-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="advanceDeductionSheettable" class="table table-striped table-hover">
						<thead>
							<tr> 	
							    <th>E-Code</th> 					
								<th>Name</th> 
								<th>Department</th>  
								<th>Designation</th>
								<th>Type</th>   
								<th>Amount</th>    
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="attAdvanceDeductionSummarytable" style="display:none;">
		<div class="col-sm-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="advanceDeductionSummarytable" class="table table-striped table-hover">
						<thead>
							<tr> 	
								<th>Department</th>  
								<th>Strength</th>
								<th>Type</th>   
								<th>Amount</th>    
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
<script src="{{ asset('js/hrpayroll/reports/monthly_salary_report.js') }}"></script> 
<script src="{{ asset('plugins/jquery-toast-plugin/dist/jquery.toast.min.js')}}"></script>        
<script src="{{ asset('js/alerts.js')}}"></script>
@endpush
@endsection
