@extends('layouts.main') 
@section('title', 'User Type')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush

<div class="container-fluid">
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<div class="row">
		<div class="offset-3 col-6">
			<div class="card card-border">
				<form class="forms-sample" method="POST" action="{{ route('user-type-save') }}" >
				<div class="card-header">
					<div class="col-5 header-title">
						<h3>{{ __('User Type')}}</h3>
					</div>

					<div class="offset-2 col-5 header-btns">
							<button  type="submit" class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
							<a  class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body">
						@csrf

						<div class="form-group row">
							<label for="vname" class="col-sm-3 col-form-label">{{ __('Name')}}</label>
							<div class="col-sm-9">
								<input name="vname" id="vname" type="text" class="form-control input-sm" width="276" placeholder="Name" />
							</div>
							<div class="help-block with-errors"></div>

							@error('vname')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
						
						<input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="offset-3 col-6">
			<div class="card p-3">
				<div class="card-body">
					<table id="userCompanyTable" class="table">
						<thead>
							<tr>  
								<th>{{ __('Name')}}</th>       
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
