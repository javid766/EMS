@extends('layouts.main') 
@section('title', 'User Location')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush

<div class="container-fluid">
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<div class="row">
		<div class="offset-sm-3 col-sm-6">
			<div class="card card-border">
				<form class="forms-sample" method="POST" action="{{ route('user-location-save') }}" >
					<div class="card-header">
						<div class="col-sm-5 header-title">
							<h3>{{ __('User Location')}}</h3>
						</div>

						<div class="offset-sm-2 col-sm-5 header-btns">
							<button  type="submit" class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
							<a  class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body">
						@csrf

						<div class="form-group row">
							<label for="vname" class="col-sm-3 col-form-label">{{ __('Name')}}</label>
							<div class="col-sm-9">
								<input name="vname" id="vname" type="text" class="form-control input-sm" width="276" placeholder="Name" required="" />
							</div>
							<div class="help-block with-errors"></div>

							@error('vname')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
						<div class="form-group row">
							<label for="location" class="col-sm-3 col-form-label">{{ __('Location')}}</label>
							<div class="col-sm-9">
								{!! Form::select('locationid',$locations, null, ['class'=>'form-control select2', 'id'=> 'location', 'placeholder'=>'--- Select ---','required'=> 'required']) !!}

							</div>
							<div class="help-block with-errors"></div>

							@error('location')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
						<div class="form-group row">
							<label for="user" class="col-sm-3 col-form-label">{{ __('User')}}</label>
							<div class="col-sm-9">
								{!! Form::select('userid',$users, null, ['class'=>'form-control select2', 'id'=> 'user', 'placeholder'=>'--- Select ---','required'=> 'required']) !!}

							</div>
							<div class="help-block with-errors"></div>

							@error('user')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
						<div class="form-group row">
							<label for="isactive" class="col-sm-3 col-form-label">Is Active</label>
							<div class="col-sm-9">
								<input id="isactive" class="align-checkbox" type="checkbox" name="isactive" value="1">
							</div>
							<div class="help-block with-errors"></div>
							@error('isactive')
							<span class="invalid-feedback" role="alert">
								<strong>{{ $message }}</strong>
							</span>
							@enderror
						</div>
						<input type="hidden" id="id" name="id" value=""/>
						<input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="offset-sm-3 col-sm-6">
			<div class="card p-3">
				<div class="card-body">
					<table id="userLocationTable" class="table">
						<thead>
							<tr>  
								<th>{{ __('Name')}}</th>       
								<th>{{ __('User')}}</th>
								<th>{{ __('Location')}}</th>
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
<script src="{{ asset('js/user/user_location.js') }}"></script> 
@endpush
@endsection
