@extends('layouts.main') 
@section('title', 'Allowded Group')
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
				<form class="forms-sample" method="POST" action="{{url('/setup/allowed-group/save')}}" >
					<div class="card-header">
						<div class="col-sm-5 header-title">
							<h3>{{ __('Allowded Group')}}</h3>
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
									<label for="vtype" class="col-sm-3 col-form-label">{{ __('Type')}}</label>
									<div class="col-sm-9">
										<input name="vtype" id="vtype" type="text" class="form-control input-sm" width="276" value="{{ old('vtype') }}" placeholder="Type" required="" />

									</div>
									<div class="help-block with-errors"></div>
									@error('vtype')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
								</div>
								
								<div class="form-group row">

								<label for="isactive" class="col-sm-3 col-form-label">Is Active</label>
								<div class="col-sm-3">
									<span class="form-control input-sm input-checkbox"><input id="isactive" type="checkbox" name="isactive" value="1"></span>
								</div>
								<div class="help-block with-errors"></div>
								@error('isactive')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
								<label for="isallowance" class="col-sm-3 col-form-label">Is Allowance</label>
								<div class="col-sm-3">
									<span class="form-control input-sm input-checkbox"><input id="isallowance" type="checkbox" name="isallowance" value="1"></span>
								</div>
								<div class="help-block with-errors"></div>
								@error('isallowance')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
								@enderror
							</div>
							</div>
							
						</div>
						<input name="id" id="id" type="hidden" value="{{old('id')}}"/>
						<input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="offset-sm-3 col-sm-6">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="allowedGroupTable" class="table table-bordered table-striped table-hover">
						<thead>
							<tr>           
								<th>{{ __('Type')}}</th>
								<th>{{ __('Is Allowance')}}</th>
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
<script src="{{ asset('js/hrpayroll/setup/allowed_group.js') }}"></script> 
@endpush
@endsection
