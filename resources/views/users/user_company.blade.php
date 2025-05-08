@extends('layouts.main') 
@section('title', 'User Company')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush

<div class="container-fluid">
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<div class="row">
		<div class="offset-sm-1 col-sm-10">
			<div class="card card-border">
				<form class="forms-sample" method="POST" action="{{ route('user-company-save') }}" >
					<div class="card-header">
						<div class="col-sm-5 header-title">
							<h3>{{ __('User Company')}}</h3>
						</div>

						<div class="offset-sm-2 col-sm-5 header-btns">
							<button  type="submit" class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
							<a  class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body">
						@csrf
						<div class="form-group row">
							<div class="col-sm-4">
								<label for="soudu_name" class="col-form-label">{{ __('Name')}}</label>
								<input name="soudu_name" id="soudu_name" type="text" class="form-control input-sm" width="276" placeholder="Name" required="" />
								<div class="help-block with-errors"></div>

								@error('soudu_name')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
							<div class="col-sm-4">
								<label for="soudu_email" class="col-form-label">{{ __('Email')}}</label>
								<input name="soudu_email" id="soudu_email" type="text" class="form-control input-sm" width="276" placeholder="Email" required="" />
								<div class="help-block with-errors"></div>

								@error('soudu_email')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
							<div class="col-sm-4">
								<label for="soudu_phoneno" class="col-form-label">{{ __('Phone')}}</label>
								<input name="soudu_phoneno" id="soudu_phoneno" type="text" class="form-control input-sm" width="276" placeholder="Phone" required="" />
								<div class="help-block with-errors"></div>

								@error('soudu_phoneno')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
						
						<div class="form-group row">
							<div class="col-sm-4">
								<label for="company" class="col-form-label">{{ __('Company')}}</label>
								{!! Form::select('companyid', $companies, null,[ 'class'=>'form-control select2', 'id'=> 'companyid', 'placeholder' => '--- Select ---','required'=> 'required']) !!}
								<div class="help-block with-errors"></div>

								@error('companyid')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
							<div class="col-sm-4">
								<label for="user" class="col-form-label">{{ __('User')}}</label>
								{!! Form::select('userid',$users, null, ['class'=>'form-control select2', 'id'=> 'user', 'placeholder' => '--- Select ---','required'=> 'required']) !!}
								<div class="help-block with-errors"></div>

								@error('user')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
							<div class="col-sm-4">
								<label for="isdefault" class="col-form-label">{{ __('Is Default')}}</label>
								<span class="form-control input-sm input-checkbox"><input name="isdefault" id="isdefault" type="checkbox" value="1" /></span>
								<div class="help-block with-errors"></div>

								@error('isdefault')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
						<div class="form-group row">
							<div class="col-sm-4">
								<label for="isactive" class="col-form-label">{{ __('Is Active')}}</label>
								<span class="form-control input-sm input-checkbox"><input name="isactive" id="isactive" type="checkbox"  value="1"/></span>
								<div class="help-block with-errors"></div>

								@error('isactive')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
						</div>
						<input name="id" id="id" type="hidden" value=""/>
						<input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="offset-sm-1 col-sm-10">
			<div class="card p-3">
				<div class="card-body">
					<table id="userCompanyTable" class="table">
						<thead>
							<tr>  
								<th>{{ __('Name')}}</th>       
								<th>{{ __('Email')}}</th>
								<th>{{ __('Phone')}}</th>
								<th>{{ __('Company')}}</th>
								<th>{{ __('User')}}</th>
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
<script src="{{ asset('js/user/user_company.js') }}"></script> 
@endpush
@endsection
