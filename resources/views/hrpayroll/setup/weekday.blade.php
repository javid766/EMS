@extends('layouts.main') 
@section('title', 'Weekday')
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
				<form class="forms-sample" method="POST" action="{{url('setup/weekday/save')}}" >
					<div class="card-header">
						<div class="col-sm-5 header-title">
							<h3>{{ __('Weekday')}}</h3>
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
									<label for="vcode" class="col-sm-3 col-form-label">{{ __('Code')}}</label>
									<div class="col-sm-9">
										<input name="vcode" id="vcode" type="text" class="form-control input-sm" width="276" value="{{ old('vcode') }}" placeholder="Code" required="" />
									</div>
									<div class="help-block with-errors"></div>
									@error('vcode')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
								</div>
								<div class="form-group row">
									<label for="vname" class="col-sm-3 col-form-label">{{ __('Title')}}</label>
									<div class="col-sm-9">
										<input name="vname" id="vname" type="text" class="form-control input-sm" width="276" value="{{ old('vname') }}" placeholder="Title" required="" />

									</div>
									<div class="help-block with-errors"></div>
									@error('vname')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
								</div>
								<div class="form-group row">
									<label for="country" class="col-sm-3 col-form-label">{{ __('Weekday')}}</label>
									<div class="col-sm-9">
										 {!! Form::select('weekday', $weekdays, null,[ 'class'=>'form-control select2', 'id'=> 'weekday', 'placeholder' => '--- Select ---','required'=> 'required']) !!}

									</div>
									<div class="help-block with-errors"></div>
									@error('weekday ')
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
							</div>
							<input name="id" id="id" type="hidden" value="{{old('id')}}"/>
							<input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="offset-sm-3 col-sm-6">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="weekdayTable" class="table table-bordered table-striped table-hover">
						<thead>
							<tr>           
								<th>{{ __('Code')}}</th>
								<th>{{ __('Title')}}</th>
								<th>{{ __('Weekday')}}</th>
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
<!-- push external js -->
@push('script') 
<script src="{{ asset('js/hrpayroll/setup/weekday.js') }}"></script> 
@endpush
@endsection
