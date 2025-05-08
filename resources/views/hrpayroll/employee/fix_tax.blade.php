@extends('layouts.main') 
@section('title', 'Employee Fix Tax')
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
				<form class="forms-sample" method="POST" action="{{url('employee/fix-tax/save')}}" >
					<div class="card-header">
						<div class="col-sm-5 header-title">
							<h3>{{ __('Employee Fix Tax')}}</h3>
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

									<div class="col-sm-4">
										<label for="employeeid" class="col-form-label">{{ __('Employee Name')}}</label>
										 {!! Form::select('employeeid', $empNames, null,[ 'class'=>'form-control select2', 'id'=> 'employeeid', 'placeholder'=> '--- Select Employee --', 'required'=> 'required']) !!}
									</div>
									<div class="col-sm-4">
									    <label for="amount" class="col-form-label">{{ __('Amount')}}</label>
										<input name="amount" id="amount" type="number" value="{{old('amount')}}" class="form-control input-sm" width="276" placeholder="00.00" required="" step="0.01" onkeydown="return event.charCode != 45 && event.keyCode !== 69 && event.keyCode !== 189" />
								    </div>
								    <div class="col-sm-4">
										<label for="vdate" class="col-form-label">{{ __('Date To')}}</label>
										<input name="vdate" id="vdate" type="date"  value="{{old('vdate')}}" class="form-control input-sm" width="276" required="" />
									</div>
									 <div class="col-sm-4">
										<label for="vdate" class="col-form-label">{{ __('Date From')}}</label>
										<input name="vdatefrom" id="vdatefrom" type="date"  value="{{old('vdatefrom')}}" class="form-control input-sm" width="276" required="" />
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
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="empFixTaxTable" class="table table-bordered table-striped table-hover">
						<thead>
							<tr>           
								<th>{{ __('Employee')}}</th>
								<th>{{ __('Amount')}}</th>
								<th>{{ __('Date To')}}</th>
								<th>{{ __('Date From')}}</th>
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
<script src="{{ asset('js/hrpayroll/employee/fix_tax.js') }}"></script> 
@endpush
@endsection
