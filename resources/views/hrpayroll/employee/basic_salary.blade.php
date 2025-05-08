@extends('layouts.main') 
@section('title', 'Basic Salary')
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
				<form class="forms-sample" method="POST" action="{{url('setup/religion/save')}}" >
					<div class="card-header">
						<div class="col-5 header-title">
							<h3>{{ __('Basic Salary')}}</h3>
						</div>
						<div class="offset-sm-2 col-sm-5 header-btns">
							<button  id="save-button" class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
							<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body">	
						@csrf
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group row">
									<label for="vcode" class="col-sm-2 col-form-label">{{ __('Employee Code')}}</label>
									<div class="col-sm-2 pl-0">
										<input name="vcode" id="vcode" type="text" class="form-control input-sm" width="276" placeholder="Employee Code"  />
									</div>
									<div class="help-block with-errors"></div>
									@error('vcode')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror

									<label for="vname" class="col-sm-2 col-form-label">{{ __('Employee Name')}}</label>
									<div class="col-sm-3 pl-0">
										<input name="vname" id="vname" type="text" class="form-control input-sm" width="276" placeholder="Employee Name"  />

									</div>
									<div class="help-block with-errors"></div>
									@error('vname')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror

									<label for="vname" class="col-sm-1 col-form-label">{{ __('Amount')}}</label>
									<div class="col-sm-2 pl-0">
										<input name="vname" id="vname" type="number" class="form-control input-sm" width="276" placeholder="00.00"  />

									</div>
									<div class="help-block with-errors"></div>
									@error('vname')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror

								</div>
							</div>
							<input name="id" id="id" type="hidden" value=""/>
							<input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="basicSalaryTable" class="table table-bordered table-striped table-hover">
						<thead>
							<tr>           
								<th>{{ __('Name')}}</th>
								<th>{{ __('Department')}}</th>
								<th>{{ __('Designation')}}</th>
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
<script src="{{ asset('js/hrpayroll/employee/basic_salary.js') }}"></script> 
@endpush
@endsection
