@extends('layouts.main') 
@section('title', 'Salary Posting')
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
				<form class="forms-sample" method="POST" action="{{url('posting/salary-posting/save')}}" >
					<div class="card-header">
						<div class="col-sm-5 header-title">
							<h3>{{ __('Salary Posting')}}</h3>
						</div>
						<div class="offset-sm-2 col-sm-5 header-btns">
							<button  class="btn btn-success" id ="saveBtn">{{ __('Posting')}}</button>
							<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body">	
						@csrf
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group row">
									<div class="col-sm-4">
										<label for="vdate" class="col-form-label">{{ __('Month')}}</label>
										<input name="vdate" id="vdate" type="month" class="form-control input-sm" width="276" required="" />
									</div>
									
									<div class="col-sm-4">
										<label for="employeeid" class="col-form-label">{{ __('Employee Name')}}</label>
										{!! Form::select('employeeid', $empNames, null,[ 'class'=>'form-control select2', 'id'=> 'employeeid', 'placeholder'=> '--- Select Employee ---']) !!}
									</div>

									<!--<div class="col-sm-2">
										<label for="allemps" class="col-form-label">{{ __('All')}}</label>                           
										<span class="form-control input-sm input-checkbox">
											<input  name="allemps" id="allemps" type="checkbox" width="276" value="1"  />
										</span>
									</div>
									<div class="col-sm-2">
										<label for="all" class="col-form-label">{{ __('Retain Old Salary')}}</label>                             
										<span class="form-control input-sm input-checkbox">
											<input  name="all" id="all" type="checkbox" width="276" value="1"  />
										</span>
									</div>-->
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row" style="display:none;">
		<div class="col-sm-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="salaryPostingTable" class="table table-bordered table-striped table-hover">
						<thead>
							<tr>           
								<th>{{ __('Date')}}</th>
								<th>{{ __('Employee')}}</th>
								<th>{{ __('Amount')}}</th>
								<th>{{ __('Action')}}</th>

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
<script src="{{ asset('js/hrpayroll/posting/salary_posting.js') }}"></script> 
@endpush
@endsection
