@extends('layouts.main') 
@section('title', 'Employee Transfer')
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
				<form class="forms-sample" method="POST" action="{{url('/employee/employeetransfer/save')}}">
					<div class="card-header">
						<div class="col-5 header-title">
							<h3>{{ __('Employee Transfer')}}</h3>
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
										<label for="employeeid" class="col-form-label">{{ __('Employee Name')}}</label>
										 {!! Form::select('employeeid', $empNames, null,[ 'class'=>'form-control select2', 'id'=> 'employeeid', 'placeholder'=> '--- Select Employee ---', 'required'=> 'required']) !!}
									</div>
									<div class="col-sm-4" style="display: none;">
										<label for="deptid" class="col-form-label">{{ __('E-Type')}}</label>
										{!! Form::select('etypeid',$allETypes, null, ['class'=>'form-control select2', 'id'=> 'etypeid']) !!}
									</div>
									<div class="col-sm-4">
										<label for="vdate" class="col-form-label">{{ __('Effective Date')}}</label>
										<input name="vdate" id="vdate" type="date" class="form-control input-sm" width="276" placeholder="DD/MM/YYYY" value="{{old('vdate')}}" />
									</div>
									<div class="col-sm-4">
										<label for="desgid" class="col-form-label">{{ __('New location')}}</label>
										{!! Form::select('locationid',$locations, null, ['class'=>'form-control select2', 'id'=> 'locationid', 'required'=> 'required', 'placeholder' =>'--- Select Location ---']) !!}
									</div>
								</div>						

								<div class="form-group row">					
									<div class="col-sm-4">
										<label for="remarks" class="col-form-label">{{ __('Remarks')}}</label>
										<input name="remarks" id="remarks" type="text" class="form-control input-sm" width="276" placeholder="Remarks"  value="{{old('remarks')}}" />
									</div>

								</div>

								<input name="id" id="id" type="hidden" value="{{old('id')}}"/>
								<input name="oldlocationid" id="oldlocationid" type="hidden" value="{{old('oldlocationid')}}"/>
								<input name="companyid" id="companyid" type="hidden" value="1"/>
								<input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
							</div>
							<div class="col-3"></div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<div class="card p-3 card-border">
				<div class="card-body table-responsive-sm">
					<table id="empTransferTable" class="table table-bordered table-striped table-hover">
						<thead>
							<tr>        
								<th>{{ __('Employee')}}</th>   							
								<th>{{ __('New Location')}}</th>
								<th>{{ __('Old Location')}}</th>
								<th>{{ __('Date')}}</th>
								<th>{{ __('Remarks')}}</th>
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
<script src="{{ asset('js/hrpayroll/employee/employee_transfer.js') }}"></script> 
@endpush
@endsection
