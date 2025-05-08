@extends('layouts.main') 
@section('title', 'Change Password')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush
<div class="container-fluid">
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<div class="row">
		<div class="offset-lg-3 col-lg-6 offset-md-2 col-md-8 col-sm-10 offset-sm-1">
			<div class="card card-border">
				<form class="forms-sample" method="POST" action="{{route('change-password-save')}}" >
					<div class="card-header">
						<div class="col-lg-9 col-md-9 col-sm-9 header-title">
							<h3>{{ __('Change Password')}}</h3>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 header-btns">
							<button class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
						</div>
					</div>
					<div class="card-body">	
						@csrf
						<div class="row">
							<div class="col-sm-12">

								<div class="form-group row">
									<label for="oldpassword" class="col-sm-4 col-form-label">{{ __('Old Password')}}</label>
									<div class="col-sm-8">
										<input id="oldpassword" type="password" class="form-control " name="oldpassword" value="" placeholder="Old Password" required>
									</div>
									<div class="help-block with-errors"></div>
									@error('oldpassword')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
								</div>
								<div class="form-group row">
									<label for="password" class="col-sm-4 col-form-label">{{ __('Password')}}</label>
									<div class="col-sm-8">
										<input id="password" type="password" class="form-control " name="password" value="" placeholder="Password" required>
										<span id="wrong-pwd-msg" style="display:none; color: red;">Invalid Password</span>
									</div>
									<div class="help-block with-errors"></div>
									@error('password')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror

								</div>
								<div class="form-group row">
									<label for="password_confirmation" class="col-sm-4 col-form-label">{{ __('Confirm Password')}}</label>
									<div class="col-sm-8">
										<input id="password_confirmation" type="password" class="form-control " name="password_confirmation" value="" placeholder="Confirm Password" required>
									</div>
									<div class="help-block with-errors"></div>
									@error('password_confirmation')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
								</div>
								
							</div>
							<input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
						</div>
					</div>
					<div class="card-footer">
						
						<p style="color: red;"><b>Note:</b> Password must include at least 6 characters.
							These Characters <br>( <b> - : ' ; * </b> ) are not allowded.
						</p>
						
					</div>
				</form>

			</div>
		</div>
	</div>
</div>
<!-- push external js -->

@push('script')
<script type="text/javascript">
	
//password validation
$("#password").on("input", function () {
	var pwd = $(this).val();
	var specialChars = /[ *:;'-]/;
	var check = (specialChars.test(pwd));
	
	if (check){
		$('#wrong-pwd-msg').css('display','block');
		
	} else {
		$('#wrong-pwd-msg').css('display','none');
	}
});

$("#password").on("change", function () {

	var pwdlength = $(this).val().length;
	if (pwdlength<6 && pwdlength>0){
		$('#wrong-pwd-msg').css('display','block');
		
	} else {
		$('#wrong-pwd-msg').css('display','none');
	}
});
</script>
@endpush
@endsection
