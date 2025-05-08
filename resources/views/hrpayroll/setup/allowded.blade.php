@extends('layouts.main') 
@section('title', 'Allowded')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush
<div class="container-fluid">
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<div class="row">
		<div class="col-sm-8 offset-sm-2">
			<div class="card card-border">
				<form class="forms-sample" method="POST" action="{{url('/setup/allowded/save')}}" >
					<div class="card-header">
						<div class="col-sm-5 header-title">
							<h3>{{ __('Allowance / Deduction')}}</h3>
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
									<div class="col-sm-4">
										<input name="vcode" id="vcode" type="text" class="form-control input-sm" width="276" value="{{ old('vcode') }}" placeholder="Code"  required=""/>
									</div>
									
									<div class="help-block with-errors"></div>
									@error('vcode')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror	
									
									<label for="vname" class="col-sm-2 col-form-label">{{ __('Title')}}</label>
									<div class="col-sm-3">
										<input name="vname" id="vname" type="text" class="form-control input-sm" width="276" value="{{ old('vname') }}" placeholder="Title" required />
									</div>
									<div class="help-block with-errors"></div>
									@error('vname')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
									
									
								</div>
								<div class="form-group row">
									
									<label for="allowdedGrp" class="col-sm-3 col-form-label">{{ __('Allowance / Deduction Group')}}</label>
									<div class="col-sm-4">
										{!! Form::select('groupid', $allowdedGrp, null,[ 'class'=>'form-control select2','placeholder'=>'--- Select ---' ,'id'=> 'allowdedGrp', 'required'=> 'required']) !!}
									</div>
									<div class="help-block with-errors"></div>
									@error('allowdedGrp')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
									
									<label for="isactive" class="col-sm-2 col-form-label">Is Active</label>
									<div class="col-sm-3">
										<span class="form-control input-sm input-checkbox"><input id="isactive" type="checkbox" name="isactive" value="1" checked=""></span>
									</div>
									<div class="help-block with-errors"></div>
									@error('isactive')
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
		<div class="col-sm-8 offset-sm-2">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="allowdedTable" class="table table-bordered table-striped table-hover">
						<thead>
							<tr>           
								<th>{{ __('Code')}}</th>
								<th>{{ __('Title')}}</th>
								<th>{{ __('Allowance / Deduction Group')}}</th>
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
<script src="{{ asset('js/hrpayroll/setup/allowded.js') }}"></script> 
@endpush
@endsection
