@extends('layouts.main') 
@section('title', 'Employee Information')
@section('content')
<!-- push external head elements to head -->
@push('head')
<style type="text/css">
	.errspan{
		font-size: 11px;
	}
</style>
@endpush
<div class="container-fluid employee-main">
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<div class="row">		
		<div class="card card-border">
			<form class="forms-sample" method="POST" action="{{url('/employee/employeeinfo/save')}}" enctype="multipart/form-data"  onsubmit="return validateEmployeeData(this)">
				<div class="card-header">
					<div class="col-sm-5 header-title">
						<h3>{{ __('Employee Information')}}</h3>
					</div>
					<div class="col-7 header-btns">
						<a href="#" class="btn btn-secondary" id ="searchBtnTrial">{{ __('From Trial')}}</a>
						<a id="searchBtn" class="btn btn-secondary">{{ __('Search')}}</a>
						<button  class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
						<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
					</div>
				</div>
				<div class="card-body">	
					@csrf
					<div class="row">
						<div class="col-sm-9">
							<div class="form-group row">
								<div class="col-sm-3">
									<label for="ecode" class="col-form-label">{{ __('Emp Code')}}<span class="text-red"> *</span></label>
									<input name="ecode" id="ecode" type="text" class="form-control input-sm ecode" width="276" placeholder="Code" value="{{old('ecode') ?? $employeecode}}" readonly />
									<input type="hidden" name="empcode" id="empcode" value="{{old('empcode') ?? $employeecode}}">
								</div>

								<div class="col-sm-3">
									<label for="machinecardno" class="col-form-label">{{ __('Machine')}}<span class="text-red"> *</span></label>
									<input name="machinecardno" id="machinecardno" type="text" class="form-control input-sm" width="276" placeholder="1234" value="{{ old('machinecardno') }}" required="" />
								</div>

								<div class="col-sm-3">
								  	<label for="etypeid" class="col-form-label">{{ __('E-Type')}}</label>							
									{!! Form::select('etypeid',$eTypes, null, ['class'=>'form-control select2', 'id'=> 'etypeid', 'placeholder'=> '--- Select ---']) !!}
								</div>

								<div class="col-sm-3">
									<label for="locationid" class="col-form-label">{{ __('Location')}}<span class="text-red"> *</span></label>
									{!! Form::select('locationid',$locations, null, ['class'=>'form-control select2', 'id'=> 'locationid', 'placeholder'=> '--- Select ---', 'required'=> 'required']) !!}
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-3">											
									<label for="employeename" class="col-form-label">{{ __('Name')}}<span class="text-red"> *</span></label>
									<input name="employeename" id="employeename" type="text" class="form-control input-sm" width="276" placeholder="Name" required="" value="{{ old('employeename') }}" />
								</div>

								<div class="col-sm-3">
								  	<label for="fathername" class="col-form-label">{{ __('Father Name')}}<span class="text-red"> *</span></label>							
									<input name="fathername" id="fathername" type="text" class="form-control input-sm" width="276" placeholder="Father Name" required="" value="{{ old('fathername') }}" />
								</div>

								<div class="col-sm-3">
								    <label for="deptid" class="col-form-label">{{ __('Department')}}<span class="text-red"> *</span></label>
							
									{!! Form::select('deptid',$allDepts, null, ['class'=>'form-control select2', 'id'=> 'deptid', 'placeholder'=> '--- Select ---', 'required'=> 'required']) !!}
								</div>
										
								<div class="col-sm-3">
									<label for="desgid" class="col-form-label">{{ __('Designation')}}<span class="text-red"> *</span></label>							
									{!! Form::select('desgid',$allDesgs, null, ['class'=>'form-control select2', 'id'=> 'desgid', 'placeholder'=> '--- Select ---', 'required'=> 'required']) !!}
								</div>	

								
							</div>

							<div class="form-group row">
								<div class="col-sm-3">
									<label for="doj" class="col-form-label">{{ __('DOJ')}}<span class="text-red"> *</span></label>
									<input name="doj" id="doj" type="date" class="form-control input-sm" width="276" placeholder="Code"  required="" value="{{ old('doj') }}"/>
									<span class="text-red errspan" id="dojerr"></span>
								</div>

								<div class="col-sm-3">
									<label for="replaceof" class="col-form-label">{{ __('Replace Of')}}</label>
									<input name="replaceof" id="replaceof" type="text" class="form-control input-sm" width="276" value="{{ old('replaceof') }}"/>
								</div>

								<div class="col-sm-3">
								  	<label for="hiretypeid" class="col-form-label">{{ __('Hire Type')}}</label>							
									{!! Form::select('hiretypeid',$hireTypes, null, ['class'=>'form-control select2', 'id'=> 'hiretypeid', 'placeholder'=> '--- Select ---']) !!}
								</div>

								<div class="col-sm-3">
								  	<label for="jobtypeid" class="col-form-label">{{ __('Job Type')}}</label>							
									{!! Form::select('jobtypeid',$probation, null, ['class'=>'form-control select2', 'id'=> 'jobtypeid', 'placeholder'=> '--- Select ---']) !!}
								</div>								
							</div>

							<div class="form-group row">
								<div class="col-sm-3">
									<label for="dob" class="col-form-label">{{ __('DOB')}}</label>
									<input name="dob" id="dob" type="date" class="form-control input-sm" width="276" value="{{ old('dob') }}"/>

								</div>

								<div class="col-sm-3">
									<label for="shiftid" class="col-form-label">{{ __('Shift')}}<span class="text-red"> *</span></label>
									{!! Form::select('shiftid',$allShifts, null, ['class'=>'form-control select2', 'id'=> 'shiftid', 'placeholder'=> '--- Select ---', 'required'=> 'required']) !!}
								</div>

								<div class="col-sm-3">
									<label for="email" class="col-form-label">{{ __('Email')}}<span class="text-red"> *</span></label>
									<input name="email" id="email" type="email" class="form-control input-sm" width="276" placeholder="Email" value="{{ old('email') }}" />
								</div>	

								<div class="col-sm-3">
									<label for="contactno" class="col-form-label">{{ __('Contact #')}}</label>
									<input name="contactno" id="contactno" type="number" class="form-control input-sm" width="276" placeholder="Contact Number" value="{{ old('contactno') }}" />
								</div>	
							</div>

						</div>
						
						<div class="form-group col-sm-3">
							<label class="col-form-label">Attach Employee Pic</label>
							<div class="emp-pic dashed-boder">							
									<div id="emppicImgPreview"></div>
									<input id="emppic" type="file" class="" name="emppic" value="{{ old('emppic') }}" placeholder="" >
									<div id="emppicResult"></div>
							</div>

						</div>

					<div class="col-sm-12">
						<div class="emp-border dashed-boder">
							<div class="form-group row">

								<div class="col-sm-2">
									<label for="cnicno" class="col-form-label">{{ __('National ID')}}<span class="text-red"> *</span></label>
									<input name="cnicno" id="cnicno" type="text" class="form-control input-sm" width="276" placeholder="xxxxx-xxxxxxxxx-x" required value="{{ old('cnicno') }}" />
									<span class="text-red errspan" id="cnicnoerr"></span>
								</div>

								<div class="col-sm-2">
									<label for="cnicexpiry" class="col-form-label">{{ __('National ID Expiry')}}</label>
									<input name="cnicexpiry" id="cnicexpiry" type="date" class="form-control input-sm" width="276"  value="{{ old('cnicexpiry') }}"/>
								</div>

								<div class="col-sm-2">
									<label for="bloodgroupid" class="col-form-label">{{ __('Blood Group')}}</label>
									{!! Form::select('bloodgroupid',$bloodgroups, null, ['class'=>'form-control select2', 'id'=> 'bloodgroupid', 'placeholder'=> '--- Select ---']) !!}					
								</div>

								<div class="col-sm-2">
									<label for="salary" class="col-form-label">{{ __('Salary')}}<span class="text-red"> *</span></label>
									<input name="salary" id="salary" type="number" class="form-control input-sm" width="276" placeholder="" required="" value="{{ old('salary') }}" onkeydown="return event.charCode != 45 && event.keyCode !== 69 && event.keyCode !== 189"/>
									<span class="errspan text-red" id="salerr"></span>
								</div>

								<div class="col-sm-2">
									<label for="incometax" class="col-form-label">{{ __('Monthly Tax')}}</label>
									<input name="incometax" id="incometax" type="number" class="form-control input-sm" width="276" value="0.00"  value="{{ old('incometax') }}" onkeydown="return event.charCode != 45 && event.keyCode !== 69 && event.keyCode !== 189"/>
								</div>

								<div class="col-sm-2">
								  	<label for="confirmationdate" class="col-form-label">{{ __('Confirmation Date')}}</label>							
									<input name="confirmationdate" id="confirmationdate" type="date" class="form-control input-sm" width="276" value="{{ old('confirmationdate') }}"  />
								</div>
							</div>

							<div class="form-group row">
								<div class="col-md-2">
									<label for="issalarytobank" class="col-form-label">{{ __('Salary To Bank')}}</label>
		                            <span class="form-control input-sm input-checkbox">
										<input name="issalarytobank" id="issalarytobank" type="checkbox" value="1" >
									</span>
		                                                               
		                        </div>

								<div class="col-sm-4">
									<label for="bankid" class="col-form-label">{{ __('Bank Account')}}</label>
									{!! Form::select('bankid',$allBanks, null, ['class'=>'form-control select2', 'id'=> 'bankid', 'placeholder' => '--- Select ---']) !!}
								</div>

								<div class="col-sm-2">
									<label for="advanceglcode" class="col-form-label">{{ __('Advance Gl Code')}}</label>
									<input name="advanceglcode" id="advanceglcode" type="text" class="form-control input-sm" width="276" placeholder=""  value="{{ old('advanceglcode') }}"  />
								</div>

								<div class="col-sm-2">
								  	<label for="offday1" class="col-form-label">{{ __('Off Day- 1')}}</label>							
									{!! Form::select('offday1id',$weekdays, null, ['class'=>'form-control select2', 'id'=> 'offday1id', 'placeholder'=> '--- Select ---']) !!}
								</div>

								<div class="col-sm-2">
								  	<label for="offday2" class="col-form-label">{{ __('Off Day- 2')}}</label>							
									{!! Form::select('offday2id',$weekdays, null, ['class'=>'form-control select2', 'id'=> 'offday2id', 'placeholder'=> '--- Select ---']) !!}
								</div>								
							</div>

							<div class="form-group row">
								
								<div class="col-sm-2">
									<label for="dol" class="col-form-label">{{ __('DOL')}}</label>
									<input name="dol" id="dol" type="date" class="form-control input-sm" width="276" value="{{ old('dol') }}" />
								</div>

								<div class="col-sm-2">
								  	<label for="leftstatusid" class="col-form-label">{{ __('Left Status')}}</label>							
									{!! Form::select('leftstatusid',$leftstatus, null, ['class'=>'form-control select2', 'id'=> 'leftstatusid', 'placeholder'=> '--- Select ---']) !!}
								</div>


								<div class="col-sm-2">
									<label for="leftreason" class="col-form-label">{{ __('Left Reason')}}</label>
									<input name="leftreason" id="leftreason" type="text" class="form-control input-sm" width="276"  value="{{ old('leftreason') }}" />
								</div>

								<div class="col-sm-2">
								  	<label for="gradeid" class="col-form-label">{{ __('Grade')}}</label>							
									{!! Form::select('gradeid',$allgrades, null, ['class'=>'form-control select2', 'id'=> 'gradeid', 'placeholder'=> '--- Select ---']) !!}
								</div>
								<div class="col-sm-2">
								  	<label for="religionid" class="col-form-label">{{ __('Religion')}}</label>							
									{!! Form::select('religionid',$allReligions, null, ['class'=>'form-control select2', 'id'=> 'religionid', 'placeholder'=> '--- Select ---']) !!}
								</div>
								<div class="col-sm-2">
								  	<label for="genderid" class="col-form-label">{{ __('Gender')}}<span class="text-red"> *</span></label>							
									{!! Form::select('genderid',$genders, null, ['class'=>'form-control select2', 'id'=> 'genderid', 'placeholder'=> '--- Select ---', 'required'=> 'required']) !!}
								</div>											
							</div>

							<div class="form-group row">
								<div class="col-sm-6">
									<label for="education" class="col-form-label">{{ __('Education')}}</label>
									<input name="education" id="education" type="text" class="form-control input-sm" width="276"  value="{{ old('education') }}" />
								</div>	
								<div class="col-sm-6">
									<label for="address" class="col-form-label">{{ __(' Address')}}</label>
									<input name="address" id="address" type="text" class="form-control input-sm" width="276" placeholder="Enter Address"  value="{{ old('address') }}"/>
								</div>						
							</div>
						</div>						
          
							<div class="form-group row">
								<div class="col-sm-3">
		                                <input  name="ishod" id="ishod" type="checkbox" width="276" value="1"/>
		                                <label for="ishod" class="col-form-label">{{ __('HOD')}}</label>                               
		                        </div>
		                        <div class="col-sm-3">
		                                <input  name="isregisterperson" id="isregisterperson" type="checkbox" width="276" value="1"/>
		                                <label for="isregisterperson" class="col-form-label">{{ __('RP-Register Person')}}</label>                               
		                        </div>
		                        <div class="col-sm-3">
		                                <input  name="haveot" id="haveot" type="checkbox" width="276" value="1"/>
		                                <label for="haveot" class="col-form-label">{{ __('Have OverTime')}}</label>                               
		                        </div>
		                        <div class="col-sm-3">
		                                <input  name="haveotoff" id="haveotoff" type="checkbox" width="276" value="1"/>
		                                <label for="haveotoff" class="col-form-label">{{ __('OFF Day OverTime')}}</label>                               
		                        </div>
		                        <div class="col-sm-3">
		                                <input  name="isstopsalary" id="isstopsalary" type="checkbox" width="276" value="1"/>
		                                <label for="isstopsalary" class="col-form-label">{{ __('Stop Salary')}}</label>                               
		                        </div>
		                        <div class="col-sm-3">
		                                <input  name="isexemptatt" id="isexemptatt" type="checkbox" width="276" value="1"/>
		                                <label for="isexemptatt" class="col-form-label">{{ __('Exempt Attendance')}}</label>                               
		                        </div>
      
		                         <div class="col-sm-3">
		                                <input  name="isexemptlatecoming" id="isexemptlatecoming" type="checkbox" width="276" value="1"/>
		                                <label for="isexemptlatecoming" class="col-form-label">{{ __('Exempt Late Coming')}}</label>                               
		                        </div>
		                         <div class="col-sm-3">
		                                <input  name="isshiftemployee" id="isshiftemployee" type="checkbox" width="276" value="1"/>
		                                <label for="isshiftemployee" class="col-form-label">{{ __('Shift Employee')}}</label>                               
		                        </div>	
		                        <div class="col-sm-3">
		                                <input  name="isactive" id="isactive" type="checkbox" width="276" value="1" checked />
		                                <label for="Is Active" class="col-form-label">{{ __('isactive')}}</label>                               
		                        </div>                      
							</div>

						<input name="id" id="id" type="hidden" value="{{ old('id') }}"/>
						<input type="hidden" name="editpic" id="editpic" value="{{ old('editpic') }}">
						<input type="hidden" id="token" name="token" value="{{ csrf_token() }}">
						<input type="hidden" name="trialEmpCode" id="trialEmpCode" value="{{ old('trialEmpCode') }}">
						
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>
<!-- push external js -->
@push('script') 
<script type="text/javascript">
	 //set Today's date as default
    document.getElementById('doj').valueAsDate = new Date();
    document.getElementById('dob').valueAsDate = new Date();
    document.getElementById('cnicexpiry').valueAsDate = new Date();
    document.getElementById('dol').valueAsDate = new Date();
    document.getElementById('confirmationdate').valueAsDate = new Date();

    function validateEmployeeData(form) {
    	var count = 0;
		var salary = form.salary.value;
		var cnicno = form.cnicno.value;
		var dob    = form.dob.value;
		var doj    = form.doj.value;
		console.log(salary);
		if (salary <= 0) {
			$("#salerr").text("Salary should be greater than zero.");
			$('#salary').css('border', '1px solid red');
			count++;
		}
		if ((cnicno.length) > 15 ) {
			$("#cnicnoerr").text("National ID must be in this xxxxx-xxxxxxxxx-x format.");
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
		console.log('abcdef');
		$("#outputemppic").remove();			   
	    if(data.emppicImgsrc){
			$("#emppicImgPreview").append("<img id='outputemppic' src='" +data.emppicImgsrc+ "'>") ;
		}
		$('#ecode').val(data.empcode);
		$('#trialEmpCode').val(data.id);
		$("#id").val('');
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
	function setValueInParent(data){
		$("#outputemppic").remove();			   
	    if(data.emppicImgsrc){
			$("#emppicImgPreview").append("<img id='outputemppic' src='" +data.emppicImgsrc+ "'>") ;
		}
		$('#trialEmpCode').val('-1');
		$("#id").val(data.id);
		$('#ecode').val(data.empcode);
		$('#empcode').val(data.empcode);
		$('#editpic').val(data.emppic);
		$('#machinecardno').val(data.machinecardno);
		data.etypeid == 0 ? $('#etypeid').prop('checked', false):
		$('#etypeid').val(data.etypeid).change();	
        $('#locationid').val(data.locationid).change();
        $("#employeename").val(data.employeename);
		$("#fathername").val(data.fathername);
		$('#deptid').val(data.deptid).change();
		$('#desgid').val(data.desgid).change();
		$("#doj").val(data.doj);		
		$("#replaceof").val(data.replaceof);
		data.hiretypeid == 0 ? $('#hiretypeid').prop('checked', false):
		$('#hiretypeid').val(data.hiretypeid).change();
		data.jobtypeid == 0 ? $('#jobtypeid').prop('checked', false):
		$('#jobtypeid').val(data.jobtypeid).change();
		$("#dob").val(data.dob);
		$('#genderid').val(data.genderid).change();
		$('#shiftid').val(data.shiftid).change();
		$("#contactno").val(data.contactno);
		$("#email").val(data.email);
		$("#cnicno").val(data.cnicno);
		$("#cnicexpiry").val(data.cnicexpiry);
		data.bloodgroupid == 0 ? $('#bloodgroupid').prop('checked', false):
		$('#bloodgroupid').val(data.bloodgroupid).change();
		$("#salary").val(data.salary);
		$("#incometax").val(data.incometax);
		$("#confirmationdate").val(data.confirmationdate);
        data.issalarytobank == 1 ? $("#issalarytobank").prop('checked', true):
        $("#issalarytobank").prop('checked', false);
        data.bankaccount == 0 ? $('#bankid').prop('checked', false):
		$('#bankid').val(data.bankaccount).change();
        $("#advanceglcode").val(data.advanceglcode);
        data.offday1id == 0 ? $('#offday1id').prop('checked', false):
		$('#offday1id').val(data.offday1id).change();
		data.offday2id == 0 ? $('#offday2id').prop('checked', false):
		$('#offday2id').val(data.offday2id).change();
        $("#dol").val(data.dol);
        data.leftstatusid == 0 ? $('#leftstatusid').prop('checked', false):
		$('#leftstatusid').val(data.leftstatusid).change();
        $("#leftreason").val(data.leftreason);
        data.gradeid == 0 ? $('#gradeid').prop('checked', false):
		$('#gradeid').val(data.gradeid).change();
        data.religionid == 0 ? $('#religionid').prop('checked', false):
		$('#religionid').val(data.religionid).change();
        $("#education").val(data.education);
        $("#address").val(data.address);
        data.ishod == 1 ? $("#ishod").prop('checked', true):
        $("#ishod").prop('checked', false);
        data.isregisterperson == 1 ? $("#isregisterperson").prop('checked', true):
        $("#isregisterperson").prop('checked', false);
        data.haveot == 1 ? $("#haveot").prop('checked', true):
        $("#haveot").prop('checked', false);
        data.haveotoff == 1 ? $("#haveotoff").prop('checked', true):
        $("#haveotoff").prop('checked', false);
         data.isstopsalary == 1 ? $("#isstopsalary").prop('checked', true):
        $("#isstopsalary").prop('checked', false);
        data.isexemptatt == 1 ? $("#isexemptatt").prop('checked', true):
        $("#isexemptatt").prop('checked', false);
        data.isexemptlatecoming == 1 ? $("#isexemptlatecoming").prop('checked', true):
        $("#isexemptlatecoming").prop('checked', false);
        data.isshiftemployee == 1 ? $("#isshiftemployee").prop('checked', true):
        $("#isshiftemployee").prop('checked', false);
        data.isactive == 1 ? $("#isactive").prop('checked', true):
        $("#isactive").prop('checked', false);            	      
		}
</script>
<script src="{{ asset('js/hrpayroll/employee/trial_employee_entry.js') }}"></script>
<script src="{{ asset('js/hrpayroll/employee/employee_info.js') }}"></script>
@endpush
@endsection
