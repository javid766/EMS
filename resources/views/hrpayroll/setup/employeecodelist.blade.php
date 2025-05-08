@extends('layouts.main') 
@section('title', 'Selected Employee List')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush
<div class="container-fluid">
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<div class="row">
		<div class="offset-sm-2 col-sm-8">
			<div class="card card-border">
				<form class="forms-sample" method="POST" action="{{url('setup/religion/save')}}" >
					<div class="card-header">
						<div class="header-title">
							<h3>{{ __('Selected Employee List')}}</h3>
						</div>
					</div>
					<div class="card-body">	
						@csrf
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group row">
									<label for="vcode" class="col-sm-3 col-form-label">{{ __('Employee Code List')}}</label>
									<div class="col-sm-6">
										<input name="vcode" id="vcode" type="text" class="form-control input-sm" width="276" value="{{ old('vcode') }}"  required="" />
										<p class="form-fields-comment">e.g.  45,701,702</p>
									</div>

									<div class="col-md-3">
										<a href="#" class="btn btn-warning" id="cancelBtn" ><i class="ik ik-x"></i></a>
										<a href="#" class="btn btn-success" id="cancelBtn" ><i class="ik ik-check"></i></a>
									</div>
								</div>

								<div class="form-group row">
									<label for="vname" class="col-sm-3 col-form-label">{{ __('Worker Code List')}}</label>
									<div class="col-sm-6">
										<input name="vname" id="vname" type="text" class="form-control input-sm" width="276" value="{{ old('vname') }}"  required="" />
										<p class="form-fields-comment">e.g.  50045,50701,50702</p>
									</div>
									<div class="col-md-3">
										<a href="#" class="btn btn-warning" id="cancelBtn" ><i class="ik ik-x"></i></a>
										<a href="#" class="btn btn-success" id="cancelBtn" ><i class="ik ik-check"></i></a>
									</div>

								</div>

								<div class="form-group row">
									<label for="vname" class="col-sm-3 col-form-label">{{ __('Default Location')}}</label>
									<div class="col-sm-6">
										 {!! Form::select('allLocations', $allLocations, null,[ 'class'=>'form-control select2', 'id'=> 'allLocations', 'placeholder' => '--- Select ---','required'=> 'required']) !!}

									</div>
									<div class="col-md-3">
										<a href="#" class="btn btn-warning" id="cancelBtn" ><i class="ik ik-x"></i></a>
										<a href="#" class="btn btn-success" id="cancelBtn" ><i class="ik ik-check"></i></a>
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
</div>
<!-- push external js -->
@push('script') 
<!-- <script src="{{ asset('js/hrpayroll/setup/religion.js') }}"></script>  -->
@endpush
@endsection
