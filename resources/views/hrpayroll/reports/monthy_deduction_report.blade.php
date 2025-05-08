@extends('layouts.main') 
@section('title', 'Monthly Deduction Report')
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
	padding-top: 15px;
	padding-bottom: 15px;
}
.form-group{
	margin-bottom: 0.9em;
}
.sec-border-left{
	border-left: 1px solid #ccc;
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
							<h3>{{ __('Monthly Deduction Report')}}</h3>
						</div>
						<div class="offset-sm-2 col-sm-5 header-btns">
							<button  id="save-button" class="btn btn-default" id ="searchBtn">{{ __('Search')}}</button>
							<button  id="save-button" class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
							<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body">	
						@csrf
						<div class="row">
							<div class="col-sm-7">
								<div class="form-group row">
									<label for="deptid" class="col-sm-2 col-form-label">{{ __('Location')}}</label>
									<div class="col-sm-10">
										{!! Form::select('location',$locations, null, ['class'=>'form-control select2', 'id'=> 'locationid', 'placeholder'=> '---Select---']) !!}
									</div>
								</div>						
								<div class="form-group row">
									<label for="empcode" class="col-sm-2 col-form-label">{{ __('E-Code')}}</label>
									<div class="col-sm-3">
										<input name="empcode" id="empcode" type="text" class="form-control input-sm" width="276" placeholder="Emp Code"  />
									</div>
									<div class="col-sm-7">
										<input name="empcode" id="empcode" type="text" class="form-control input-sm" width="276" placeholder="Employee Name" disabled="" />
									</div>													
								</div>
								<div class="form-group row">
									<label for="desgid" class="col-sm-2 col-form-label">{{ __('Department')}}</label>
									<div class="col-sm-10">
										{!! Form::select('departments[]', $departments, null,[ 'class'=>'form-control select2', 'multiple' => 'multiple', 'id' => 'departmentid']) !!}
									</div>
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">{{ __('E-Type')}}</label>
									<div class="col-sm-10">
										{!! Form::select('eTypeid',$eTypes, null, ['class'=>'form-control select2', 'id'=> 'eTypeid', 'placeholder'=> '---Select---']) !!}
									</div>	
								</div>
								<div class="form-group row">
									<label for="name" class="col-sm-2 col-form-label">{{ __('Date From')}}</label>
									<div class="col-sm-4">
										<input name="datefrom" id="datefrom" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" />
									</div>
									<label for="name" class="col-sm-2 col-form-label">{{ __('Date To')}}</label>
									<div class="col-sm-4">
										<input name="dateto" id="dateto" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" />
									</div>
								</div>
								<div class="form-group sec-border">
									<div class="form-radio row">
										<div class="radio radio-inline col-sm-3">
											<label>
												<input type="radio" name="radio" checked="checked">
												<i class="helper"></i>Attendance Card
											</label>
										</div>
										<div class="radio radio-inline col-sm-4">
											<label>
												<input type="radio" name="radio">
												<i class="helper"></i>Attendance Card Compact
											</label>
										</div>
										<div class="radio radio-inline col-sm-3">
											<label>
												<input type="radio" name="radio">
												<i class="helper"></i>Absentee List
											</label>
										</div>
										<div class="radio radio-inline col-sm-3">
											<label>
												<input type="radio" name="radio">
												<i class="helper"></i>OD List
											</label>
										</div>
										<div class="radio radio-inline col-sm-4">
											<label>
												<input type="radio" name="radio">
												<i class="helper"></i>Attendance Summary
											</label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-5 sec-border-left">
								<div class="row">
									<div class="col-sm-12">
										<input id="onroll" class="col-sm-1" type="checkbox" name="onroll" value="1">
										<label for="onroll" class="col-sm-10 col-form-label">Advance Against Salary</label>
									</div>
									<div class="col-sm-12">
										<input id="onroll" class="col-sm-1" type="checkbox" name="onroll" value="1">
										<label for="onroll" class="col-sm-10 col-form-label">Deduction On Absent</label>
									</div>
									<div class="col-sm-12">
										<input id="onroll" class="col-sm-1" type="checkbox" name="onroll" value="1">
										<label for="onroll" class="col-sm-10 col-form-label">Loan Deduction</label>
									</div>
									<div class="col-sm-12">
										<input id="onroll" class="col-sm-1" type="checkbox" name="onroll" value="1">
										<label for="onroll" class="col-sm-10 col-form-label">Local Sale-Shirt</label>
									</div>
									<div class="col-sm-12">
										<input id="onroll" class="col-sm-1" type="checkbox" name="onroll" value="1">
										<label for="onroll" class="col-sm-10 col-form-label">Local Sale-Shoe</label>
									</div>
									<div class="col-sm-12">
										<input id="onroll" class="col-sm-1" type="checkbox" name="onroll" value="1">
										<label for="onroll" class="col-sm-10 col-form-label">Mess Deduction</label>
									</div>
									<div class="col-sm-12">
										<input id="onroll" class="col-sm-1" type="checkbox" name="onroll" value="1">
										<label for="onroll" class="col-sm-10 col-form-label">Provident Fund</label>
									</div>
								</div>
							</div>
						</div>
						<input name="id" id="id" type="hidden" value=""/>
						<input name="companyid" id="companyid" type="hidden" value="1"/>
						<input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
					</div>
					<div class="col-3"></div>
				</div>
			</div>
		</form>
	</div>
</div>
</div>

</div>
<!-- push external js -->
@push('script') 
<script src="{{ asset('js/hrpayroll/reports/monthly_deduction_report.js') }}"></script>
@endpush
@endsection
