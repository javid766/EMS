@extends('layouts.main') 
@section('title', 'Tenant')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush
<div class="container-fluid">
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<div class="row">
		<div class="offset-sm-2 col-sm-8">
			<div class="card card-border">
				<form class="forms-sample" method="POST" action="{{url('setup/tenant/save')}}" >
					<div class="card-header">
						<div class="col-sm-5 header-title">
							<h3>{{ __('Tenant')}}</h3>
						</div>
						<div class="col-7 header-btns">
							<button  id="save-button" class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
							<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body">	
						@csrf
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group row">
									<div class="col-sm-4">
										<label for="vcode" class="col-form-label">{{ __('Code')}}</label>
										<input name="vcode" id="vcode" type="text" class="form-control input-sm" width="276" placeholder="Tenant Code" required />
										<div class="help-block with-errors"></div>
										@error('vcode')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror

									</div>
									<div class="col-sm-4">
										<label for="vname" class="col-form-label">{{ __('Title')}}</label>

										<input name="vname" id="vname" type="text" class="form-control input-sm" width="276" placeholder="Title"  required />


										<div class="help-block with-errors"></div>
										@error('vname')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
									<div class="col-sm-4">
										<label for="currnecyname" class="col-form-label">{{ __('Currency')}}</label>

										<input name="currnecyname" id="currnecyname" type="text" class="form-control input-sm" width="276" placeholder="Currency Name" required   />


										<div class="help-block with-errors"></div>
										@error('currnecyname')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>

								</div>

								<div class="form-group row">
									<div class="col-sm-4">
										<label for="countryname" class="col-form-label">{{ __('Country')}}</label>

										<input name="countryname" id="countryname" type="text" class="form-control input-sm" width="276" placeholder="Country Name" required />

										<div class="help-block with-errors"></div>
										@error('countryname')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
									<div class="col-sm-4">
										<label for="company_nature" class="col-form-label">{{ __('Company Nature')}}</label>

										<input name="company_nature" id="company_nature" type="text" class="form-control input-sm" width="276" placeholder="Company Nature" required />


										<div class="help-block with-errors"></div>
										@error('company_nature')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
									<div class="col-sm-4">
										<label for="url" class=" col-form-label">{{ __('Url')}}</label>

										<input name="url" id="url" type="text" class="form-control input-sm" width="276" placeholder="Url"  required="" />


										<div class="help-block with-errors"></div>
										@error('url')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>	
								</div>
								
								<div class="form-group row">
									<div class="col-sm-4">
										<label for="tlogin" class="col-form-label">{{ __('Email')}}</label>
										<input name="tlogin" id="tlogin" type="email" class="form-control input-sm" width="276" placeholder="Email"  required/>

										
										<div class="help-block with-errors"></div>
										@error('tlogin')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
									<div class="col-sm-4">
										<label for="tpassword" class="col-form-label">{{ __('Password')}}</label>
										<input name="tpassword" id="tpassword" type="password" class="form-control input-sm" width="276" placeholder="Password" required />

										<div class="help-block with-errors"></div>
										@error('tpassword')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
									<div class="col-sm-4">
										<label for="isactive" class="col-form-label">{{ __('Is Active')}}</label>
										<span class="form-control input-sm input-checkbox">
											<input name="isactive" id="isactive" type="checkbox"  value="1" />
										</span>
										<div class="help-block with-errors"></div>

										@error('isactive')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>

								<div class="form-group row">
									<div class="col-sm-9">
										<input name="logindate" id="logindate" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" required="" />
									</div>
								</div>
								
							</div>
							<input name="id" id="id" type="hidden" value="{{old('id')}}"/>
						</div>
					</form>
				</div>
			</div>
		</div>

	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="tenatTable" class="table table-bordered table-striped table-hover">
						<thead>
							<tr>           
								<th>{{ __('Code')}}</th>
								<th>{{ __('Title')}}</th>
								<th>{{ __('Company')}}</th>
								<th>{{ __('Email')}}</th>
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
<script type="text/javascript">
	//set Today's date as default
	document.getElementById('logindate').valueAsDate = new Date();

</script>
<script src="{{ asset('js/hrpayroll/setup/tenat.js') }}"></script> 

@endpush
@endsection
<style>
	#logindate{
		display:none !important;
	}
</style>
