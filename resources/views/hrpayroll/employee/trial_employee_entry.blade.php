@extends('layouts.main') 
@section('title', 'Trial Employee Entry')
@section('content')
<!-- push external head elements to head -->
@push('head')
<style type="text/css">
	.errspan{
		font-size: 11px;
	}
</style>
@endpush
<div class="container-fluid">
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<div class="row">		
		<div class="card card-border">
			<form class="forms-sample" method="POST" action="{{url('/employee/trial-employee-entry/save')}}" enctype="multipart/form-data" onsubmit="return validateEmployeeData(this)">
				<div class="card-header">
					<div class="col-5 header-title">
						<h3>{{ __('Trial Employee Entry')}}</h3>
					</div>
					<div class="offset-2 col-5 header-btns">
						<a href="#" class="btn btn-secondary" id ="searchBtnTrial">{{ __('Search')}}</a>
						<button class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
						<a href="#" class="btn btn-warning" id="cancelBtnPost" >{{ __('Cancel')}}</a> 
					</div>
				</div>
				<div class="card-body">	
					@csrf
					<div class="row">
						<div class="col-sm-9">
							<div class="form-group row">
								<div class="col-sm-3">
									<label for="ecode" class="col-form-label">{{ __('Emp Code')}}<span class="text-red"> *</span></label>
									<input name="ecode" id="ecode" type="text" class="form-control input-sm ecode" width="276" placeholder="Code" value="{{$employeecode}}" readonly />
									<input type="hidden" name="empcode" id="empcode" value="{{old('empcode') ?? $employeecode}}">
								</div>
								<div class="col-sm-3">
									<label for="employeename" class="col-form-label">{{ __('Name')}}<span class="text-red"> *</span></label>
									<input name="employeename" id="employeename" type="text" class="form-control input-sm" width="276" placeholder="Name" required="" value="{{old('employeename')}}" />
								</div>


								<div class="col-sm-3">
									<label for="fathername" class="col-form-label">{{ __('Father Name')}}<span class="text-red"> *</span></label>							
									<input name="fathername" id="fathername" type="text" class="form-control input-sm" width="276" placeholder="Father Name"  required="" value="{{old('fathername')}}" />
								</div>

								<div class="col-sm-3">
									<label for="deptid" class="col-form-label">{{ __('Department')}}<span class="text-red"> *</span></label>

									{!! Form::select('deptid',$allDepts, null, ['class'=>'form-control select2', 'id'=> 'deptid', 'placeholder' =>'--- Select ---' , 'required' =>'required']) !!}
								</div>
							</div>
							
							<div class="form-group row">													
								<div class="col-sm-3">
									<label for="shiftid" class="col-form-label">{{ __('Shift')}}<span class="text-red"> *</span></label>
									{!! Form::select('shiftid',$allShifts, null, ['class'=>'form-control select2', 'id'=> 'shiftid','placeholder' => '--- Select ---' , 'required' => 'required']) !!}
								</div>	

								<div class="col-sm-3">
									<label for="doj" class="col-form-label">{{ __('DOJ')}}<span class="text-red"> *</span></label>
									<input name="doj" id="doj" type="date" class="form-control input-sm" width="276" required="" />
									<span class="text-red errspan" id="dojerr"></span>
								</div>

								<div class="col-sm-3">
									<label for="dob" class="col-form-label">{{ __('DOB')}}</label>
									<input name="dob" id="dob" type="date" class="form-control input-sm" width="276"  />
								</div>		

								<div class="col-sm-3">
									<label for="contactno" class="col-form-label">{{ __('Contact #')}}</label>
									<input name="contactno" id="contactno" type="number" class="form-control input-sm" width="276" placeholder="Contact #" value="{{old('contactno')}}" />
									<span class="text-red" id="contactnoerr"></span>
								</div>	

							</div>

							<div class="form-group row">
								<div class="col-sm-3">
									<label for="cnicno" class="col-form-label">{{ __('National ID')}}<span class="text-red"> *</span></label>
									<input name="cnicno" id="cnicno" type="text" class="form-control input-sm" width="276" placeholder="xxxxx-xxxxxxxxx-x" required="" value="{{old('cnicno')}}" />
									<span class="text-red errspan" id="cnicnoerr"></span>
								</div>

								<div class="col-sm-3">
									<label for="closingdate" class="col-form-label">{{ __('Closing Date')}}</label>
									<input name="closingdate" id="closingdate" type="date" class="form-control input-sm" width="276" />
								</div>

								<div class="col-sm-3">
									<label for="fathername" class="col-form-label">{{ __('Closing Status')}}</label>							
									{!! Form::select('closingstatus',$closingstatus, null, ['class'=>'form-control select2', 'id'=> 'closingstatus','placeholder' => '--- Select ---']) !!}
								</div>

								<div class="col-sm-3">
									<label for="fathername" class="col-form-label">{{ __('Hire Type')}}</label>							
									{!! Form::select('hiretypeid',$hireTypes, null, ['class'=>'form-control select2', 'id'=> 'hiretypeid','placeholder' => '--- Select ---']) !!}
								</div>

								<div class="col-sm-6">
									<label for="refrenceno" class="col-form-label">{{ __('Reference')}}</label>
									<input name="refrenceno" id="refrenceno" type="text" class="form-control input-sm" width="276" placeholder="Reference" value="{{old('refrenceno')}}" />
								</div>
								<div class="col-sm-6">
									<label for="address" class="col-form-label">{{ __('Permanent Address')}}</label>
									<input name="address" id="address" type="text" class="form-control input-sm" width="276" placeholder="Permanent Address"  value="{{old('address')}}" />
								</div>
							</div>

						</div>
						
						<div class="col-sm-3" style="text-align: center;">
							<label class="col-form-label">Attach Employee Pic</label>
							<div class="emp-pic dashed-boder">							
									<div id="emppicImgPreview"></div>
									<input id="emppic" type="file" class="" name="emppic" value="" placeholder="" >
									<div id="emppicResult"></div>
							</div>
						</div>
						<input name="id" id="id" type="hidden" value="{{old('id')}}"/>
						<input type="hidden" name="editpic" id="editpic" value="{{old('editpic')}}">
						<input type="hidden" id="token" name="token" value="{{ csrf_token() }}">	
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- push external js -->
@push('script') 
<script type="text/javascript">
	 //set Today's date as default
    document.getElementById('doj').valueAsDate = new Date();
    function validateEmployeeData(form) {
    	var count = 0;
		var cnicno = form.cnicno.value;
		var dob    = form.dob.value;
		var doj    = form.doj.value;

		if (  cnicno.length > 15) {
			$("#cnicnoerr").text("National ID must be in this xxxxx-xxxxxxx-x format.");
			$('#cnicno').css('border', '1px solid red');
			count++;
		}
		if (dob == doj) {
			$("#dojerr").text("Date of Joining and Date of Birth could not be same.");
			$('#doj').css('border', '1px solid red');
			count++;
		}
		if (count > 0) {
			return false;
		}
		else{
			return true;
		}
	}

	function setValueInParentTrial(data){
		$("#outputemppic").remove();			   
	    if(data.emppicImgsrc){
			$("#emppicImgPreview").append("<img id='outputemppic' src='" +data.emppicImgsrc+ "'>") ;
		}
		$("#id").val(data.id);
		$('#empcode').val(data.empcode);
        $("#employeename").val(data.employeename);
        $('#editpic').val(data.emppic);
		$("#fathername").val(data.fathername);
		$('#deptid').val(data.deptid).change();
		$('#desgid').val(data.desgid).change();
		$("#doj").val(data.doj);		
		data.hiretypeid == 0 ? $('#hiretypeid').prop('checked', false):
		$('#hiretypeid').val(data.hiretypeid).change();
		data.shiftid == 0 ? $('#shiftid').prop('checked', false):
		$('#shiftid').val(data.shiftid).change();
		data.closingstatus == 0 ? $('#closingstatus').prop('checked', false):
		$('#closingstatus').val(data.closingstatus).change();
		$("#dob").val(data.dob);
		$("#contactno").val(data.contactno);
		$("#cnicno").val(data.cnicno);
		$("#closingdate").val(data.closingdate); 
		$("#refrenceno").val(data.refrenceno);
		$("#address").val(data.address);      
		}
</script>
<script src="{{ asset('js/hrpayroll/employee/trial_employee_entry.js') }}"></script>
<script type="text/javascript">
    $('#cancelBtnPost').click(function() {
        location.reload();
    });
</script>
@endpush
@endsection
