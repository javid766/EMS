@extends('layouts.main') 
@section('title', 'Users')
@section('content')
<!-- push external head elements to head -->
@push('head')
@endpush


<div class="container-fluid">
	<div class="row">
		<!-- start message area-->
		@include('include.message')
		<!-- end message area-->
		<div class="col-md-12">
			<div class="card card-border">
				<form class="forms-sample" method="POST" action="{{ route('create-user') }}" >
					<div class="card-header">
						<div class="col-5 header-title">
							<h3>{{ __('Users')}}</h3>
						</div>
						<div class="col-7 header-btns">
							<button type="submit" id="save-button" class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
							<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>                

					<div class="card-body">

						@csrf
						<div class="row">
							<div class="col-sm-12">
								<!-- Employee Id Field -->
								<div class="form-group row">
									<div class="col-sm-3">
										<label for="empid" class="col-form-label">{{ __('Employee')}}</label>
										{!! Form::select('empid', $employees, null,[ 'class'=>'form-control select2', 'placeholder' => '--- Select ---','id'=> 'empid', 'required'=> 'required']) !!}
										<div class="help-block with-errors"></div>
										@error('empid')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>

									<div class="col-sm-3">
										<label for="name" class="col-form-label">{{ __('Full Name')}}</label>
										<input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Full Name" readonly>
										<div class="help-block with-errors"></div>
										@error('name')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>

									<div class="col-sm-3">
										<label for="email" class="col-form-label">{{ __('Email')}}</label>
										<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required readonly>
										<div class="help-block with-errors" ></div>
										@error('email')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>

									<div class="col-sm-3">
										<label for="password" class="col-form-label">{{ __('Password')}}</label>
										<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter password" value="{{ old('password') }}" required>
										<span id="wrong-pwd-msg" style="display:none; color: red;">Invalid Password</span>
										<div class="help-block with-errors"></div>

										@error('password')
										<span class="invalid-feedback" role="alert">
											<strong>{{ $message }}</strong>
										</span>
										@enderror
									</div>
								</div>

								<div class="form-group row">

									<div class="col-sm-3">
										<label for="default_companyid" class="col-form-label">{{ __('Company')}}</label>
										{!! Form::select('companies[]', $companies, null,[ 'class'=>'form-control select2',  'multiple' => 'multiple', 'id' => 'companies', 'required'=> 'required']) !!}
									</div>

									<div class="col-sm-3">
										<label for="roleid" class="col-form-label">{{ __('Assign Role')}}</label>
										{!! Form::select('roleid[]', $roles, null,[ 'class'=>'form-control select2','id'=> 'role','multiple' => 'multiple', 'required'=> 'required']) !!}
									</div>

									<div class="col-sm-3">
										<label for="isactive" class="col-form-label">Is Active</label>
										<span class="form-control input-sm input-checkbox"><input id="isactive" type="checkbox" name="isactive" value="1" checked></span>
									</div>
								</div>
							</div>
						</div>
						<br>
						<p style="color: red;"><b>Note:</b> Password must include at least 6 characters.
								These Characters ( <b> - : ' ; * </b> ) are not allowded.
						</p>
					</div>
					 <input type="hidden" name="comanyids[]" id="comanyids">
					 <input type="hidden" name="attachedComanyIds[]" id="attachedComanyIds">
					 <input type="hidden" name="locationid" id="locationid" value="{{old('locationid')}}">
					<input type="hidden" name="id" id="id" value="{{old('id')}}">

					<!-- Default fields -->
					<input id="isindividual" type="hidden" name="isindividual" value="1">
					<input id="isadmin" type="hidden" name="isadmin" value="1">
					<input id="allowactual" type="hidden" name="allowactual" value="1">
					<input id="allowaudit" type="hidden" name="allowaudit" value="1">
					<input id="usersec_flage" type="hidden" name="usersec_flage" value="1">
					<input id="usertype" type="hidden" name="usertype" value="1">
					<input id="projectid" type="hidden" name="projectid" value="1">
				</form>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="card p-3">
				<div class="card-body">
					<table id="usersTable" class="table">
						<thead>
							<tr>  
								<th>{{ __('Name')}}</th>
								<th>{{ __('Email')}}</th>
								<th>{{ __('Role')}}</th>
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
<script src="{{ asset('js/user/users.js') }}"></script> 
<script type="text/javascript">
	$("#empid").change(function (){
		var empid = $('#empid').val();
		if (empid != '') {
				$.ajax({
				url: 'user/getEmpData/'+empid,
				type: 'get',
				dataType:"JSON",
				data: {'empid': empid},
				success:function(data){
					if (data["employee"]["employeename"] == undefined) {
						document.getElementById("name").value = '';
					}
					else{
						document.getElementById("name").value = data["employee"]["employeename"]+' '+data["employee"]["fathername"];
					}
					if (data["employee"]["email"] == undefined) {
						document.getElementById("email").value = '';
					}
					else{
						document.getElementById("email").value = data["employee"]["email"];
					}
					if (data["employee"]["locationid"] == '') {
						document.getElementById("locationid").value = '';
					}
					else{
						document.getElementById("locationid").value = data["employee"]["locationid"];
					}
					$('#comanyids').val(data["user_company_ids"]);
        			$('#attachedComanyIds').val(data["user_companies"]);
					$('#attachedLocationIds').val(data["user_locations"]);
					$('#tid').val(data["tid"]).change();
					$('#companies').val(data["user_companies"]).change();
					$('#role').val(data["user_role"]).change();
					$("#isactive").prop('checked', (data['isactive'] == 1 ? true : false));
				}
			})
		}
		else{
			document.getElementById("name").value = '';
			document.getElementById("email").value = '';
			document.getElementById("password").value = '';
			$('#comanyids').val('');
			$('#attachedComanyIds').val('');
			$('#attachedLocationIds').val('');
			$('#tid').val('').change();
			$('#companies').val('').change();''
			$('#locations').val('').change();
			$('#role').val('').change();	
			$("#isactive").prop('checked', false);

		}
	});

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
