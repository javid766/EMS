@extends('layouts.main') 
@section('title', 'Switch Credentials')
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
				<form class="forms-sample" method="POST" action="{{url('/user/switch-credentials/save')}}" >
					<div class="card-header">
						<div class="col-5 header-title">
							<h3>{{ __('Switch Credentials')}}</h3>
						</div>
						
					</div>
					<div class="card-body">	
						@csrf
						<div class="row">
							<div class="col-sm-12">

								<div class="form-group row">
									<label for="company" class="col-sm-3 col-form-label">{{ __('Company')}}</label>
									<div class="col-sm-9">
										{!! Form::select('companyid', $companies,$selectedComp,[ 'class'=>'form-control select2', 'id'=> 'company', 'required'=> 'required']) !!}

									</div>
									<div class="help-block with-errors"></div>
									@error('companyid')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
								</div>
								<div class="form-group row">
									<label for="financialyear" class="col-sm-3 col-form-label">{{ __('Financial Years')}}</label>
									<div class="col-sm-9">
										{!! Form::select('financialyearid', $financialYears,$selectedYear,[ 'class'=>'form-control select2', 'id'=> 'financialyear', 'required'=> 'required']) !!}

									</div>
									<div class="help-block with-errors"></div>
									@error('financialyearid')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
								</div>
								
								<div class="form-group row">
									<label for="project" class="col-sm-3 col-form-label">{{ __('Project')}}</label>
									<div class="col-sm-9">
										{!! Form::select('projectid', $projects,$selectedProject,[ 'class'=>'form-control select2', 'id'=> 'project', 'required'=> 'required']) !!}

									</div>
									<div class="help-block with-errors"></div>
									@error('projectid')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
								</div>
								
								
							</div>
							<input type="hidden" id="companyname" name="companyname" value='{{$selectedCompname}}'>
							<input type="hidden" id="projectname" name="projectname" value='{{$selectedProjectname}}'>
							
							<input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
						</div>
					</div>
					<div class="card-footer">
						<div class="offset-12 header-btns">
							<button  id="save-button" class="btn btn-success" id ="saveBtn">{{ __('Switch Credentials')}}</button>
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>
</div>
<!-- push external js -->

@push('script')
<script src="{{ asset('js/user/switch_credentials.js') }}"></script> 
@endpush
@endsection
