@extends('layouts.main') 
@section('title', 'Shift')
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
				<form class="forms-sample" method="POST" action="{{ route('setup-shift-save') }}" >
					<div class="card-header">
						<div class="col-sm-5 header-title">
							<h3>{{ __('Shift')}}</h3>
						</div>
						<div class="col-7 header-btns">
							<button  class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
							<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body">	
						@csrf
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group row">
									<div class="col-sm-2">
										<label for="vcode" class="col-form-label">{{ __('Code')}}
										</label>
										<input name="vcode" id="vcode" type="text" class="form-control input-sm" width="276" value="{{ old('vcode') }}" placeholder="Code"  required="" />
									</div>
									<div class="help-block with-errors"></div>
									@error('vcode')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
									<div class="col-sm-4">
										<label for="vname" class="col-form-label">{{ __('Title')}}
										</label>
										<input name="vname" id="vname" type="text" class="form-control input-sm" width="276" value="{{ old('vname') }}" placeholder="Title" required="" />
									</div>
									<div class="help-block with-errors"></div>
									@error('vname')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
									<div class="col-sm-2">
										<label for="timein" class="col-form-label">{{ __('Time In')}}
										</label>
										<input name="timein" id="timein" type="time" step="any" class="form-control input-sm" width="276" value="{{ old('timein') }}" placeholder="00:00"
										maxlength="5" required="" onkeypress="return isNumberKey(event)" />
									</div>
									<div class="help-block with-errors"></div>
									@error('timein')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
									<div class="col-sm-2">
										<label for="timeout" class="col-form-label">{{ __('Time Out')}}</label>
										<input name="timeout" id="timeout" type="time" step="any" class="form-control input-sm" width="276" value="{{ old('timeout') }}" placeholder="00:00" maxlength="5" required="" onkeypress="return isNumberKey(event)" />
									</div>
									<div class="help-block with-errors"></div>
									@error('timeout')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
									<div class="col-sm-2">
										<label for="workinghrs" class="col-form-label">{{ __('Working Hrs.')}}</label>
										<input name="workinghrs" id="workinghrs" type="number" step="any"  class="form-control input-sm" width="276" value="{{ old('workinghrs') }}" placeholder="Working Hrs." maxlength="5" required="" onkeypress="return isNumberKey(event)" />
									</div>
									<div class="help-block with-errors"></div>
									@error('workinghrs')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror									
								</div>

								<div class="form-group row">
									<div class="col-sm-2">
										<label for="resttimefrom" class="col-form-label">{{ __('Rest From')}}</label>
										<input name="resttimefrom" id="resttimefrom" type="time" class="form-control input-sm" width="276" value="{{ old('resttimefrom') }}" placeholder="00:00"  step="any" maxlength="5"  required="" onkeypress="return isNumberKey(event)" />
									</div>

									<div class="col-sm-2">
										<label for="resttimeto" class="col-form-label">{{ __('Rest To')}}</label>
										<input name="resttimeto" id="resttimeto" type="time" class="form-control input-sm" width="276" value="{{ old('resttimeto') }}" placeholder="00:00"  step="any" maxlength="5"  required="" onkeypress="return isNumberKey(event)" />
									</div>
									<div class="help-block with-errors"></div>
									@error('resttimeto')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
									<div class="col-sm-2">
										<label for="relaxtime" class="col-form-label">{{ __('Relax Time')}}</label>
										<input name="relaxtime" id="relaxtime" type="time" class="form-control input-sm" width="276" value="{{ old('relaxtime') }}" placeholder="00:00" step="any" maxlength="5" required="" onkeypress="return isNumberKey(event)" />
									</div>
									<div class="help-block with-errors"></div>
									@error('relaxtime')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
									<div class="col-sm-2">
										<label for="minatttime" class="col-form-label">{{ __('MinAtt Time')}}</label>
										<input name="minatttime" id="minatttime" type="time" class="form-control input-sm" width="276" value="{{ old('minatttime') }}" placeholder="00:00"  step="any" maxlength="5"  required="" onkeypress="return isNumberKey(event)" />
									</div>
									<div class="help-block with-errors"></div>
									@error('minatttime')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
									<div class="col-sm-2">
										<label for="minhdtime" class="col-form-label">{{ __('MinHD Time')}}</label>
										<input name="minhdtime" id="minhdtime" type="time" class="form-control input-sm" width="276" value="{{ old('minhdtime') }}" placeholder="00:00"  step="any" maxlength="5"  required="" onkeypress="return isNumberKey(event)" />
									</div>
									<div class="help-block with-errors"></div>
									@error('minhdtime')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror									
									<div class="col-sm-2">
										<label for="isroster" class="col-form-label">{{ __('Roster')}}</label>
										<span class="form-control input-sm input-checkbox">
										<input name="isroster" id="isroster" type="checkbox" width="276" value="1" />
									    </span>
									</div>
									<div class="help-block with-errors"></div>
									@error('isroster')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror	
								</div>
								<div class="form-group row">
									<div class="col-sm-2">
										<label for="issecurity" class="col-form-label">{{ __('Security')}}</label>
										<span class="form-control input-sm input-checkbox">
										 <input name="issecurity" id="issecurity" type="checkbox" width="276" value="1" />
									    </span>
									</div>
									<div class="col-sm-2">
										<label for="isactive" class="col-form-label">{{ __('Is Active')}}</label>
										<span class="form-control input-sm input-checkbox">
										 <input name="isactive" id="isactive" type="checkbox" width="276" value="1" checked/>
									    </span>
									</div>
								</div>
							</div>
							<input name="id" id="id" type="hidden" value="{{ old('id') }}"/>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="card p-3 card-border">
				<div class="card-body table-responsive-sm">
					<table id="shiftTable" class="table table-bordered table-striped table-hover">
						<thead>
							<tr>           
								<th>{{ __('Code')}}</th>
								<th>{{ __('Title')}}</th>
								<th>{{ __('Time In')}}</th>
								<th>{{ __('Time Out')}}</th>
								<th>{{ __('Working Hrs.')}}</th>
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
<script src="{{ asset('js/hrpayroll/setup/shift.js') }}"></script> 
@endpush
@endsection
