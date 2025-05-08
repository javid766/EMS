@extends('layouts.main') 
@section('title', 'Attendance Posting')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush
<div class="container-fluid">
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<form class="forms-sample" method="POST" action="{{url('/posting/daily-posting/save')}}" >
		<div class="row">
			<div class="col-md-12">		
				<div class="card card-border">
						<div class="card-header">
							<div class="col-5 header-title">
								<h3>{{ __('Attendance Posting')}}</h3>						
							</div>
							<div class="offset-md-2 col-md-5 header-btns">
							   <button class="btn btn-success" id="saveBtn" >{{ __('Posting')}}</button>
	                           <a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
	                    	</div>
						</div>
						<div class="card-body">	
							@csrf
							<div class="row">
								<div class="col-md-12">

									<div class="form-group row">
										<div class="col-md-3">
											<label for="deptid" class="col-form-label">{{ __(' Department')}}</label>								
											{!! Form::select('deptid',$depts, null, ['class'=>'form-control select2', 'id'=> 'deptid']) !!}
										</div>

										<div class="col-md-2">
											<label for="datefrom" class="col-form-label">{{ __('Date From')}}</label>									
											<input name="datefrom" id="datefrom" type="date" class="form-control input-sm" width="276" />
										</div>

										<div class="col-md-2">
											<label for="dateto" class="col-form-label">{{ __('Date To')}}</label>									
											<input name="dateto" id="dateto" type="date" class="form-control input-sm" width="276"/>
										</div>	

										<div class="col-md-3">
											<label for="etypeid" class="col-form-label">{{ __('E-Type')}}</label>									
											{!! Form::select('etypeid',$eTypes, null, ['class'=>'form-control select2', 'id'=> 'etypeid']) !!}
										</div>
									</div>	

									<input name="id" id="id" type="hidden" value=""/>
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>

		<div class="row"> 
	        <div class="col-md-12">   
	            <div class="card p-3 card-border">
	              <div class="card-body">
	                <table id="attPostingTable" class="table" >
	                    <thead>
	                        <tr>       
	                            <th><input type="checkbox" name="select-all-checkboxes" id="select-all-checkboxes"></th>  
	                           <!--  <th>{{ __('Emp id')}}</th> -->         
	                            <th>{{ __('E-Code')}}</th>
	                            <th>{{ __('Name')}}</th>
	                            <th>{{ __('Department')}}</th>
	                            <th>{{ __('Designation')}}</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    </tbody>
	                </table>
	           		 </div>
	        	</div>
	    	</div>
	    </div>
	</form>

</div>
<!-- push external js -->
@push('script') 
<script src="{{ asset('js/hrpayroll/posting/daily_posting.js') }}"></script> 
<script type="text/javascript">
    function getCheckedCells() {
        var dTableCardPrinting = $('#attPostingTable').DataTable();
        var selectedCells = [];
        var obj = {};
        var obj_selected = {};

        $("#attPostingTable tbody input[type=checkbox]:checked").each(function () {
            var row = $(this).closest("tr")[0];
            obj_selected['id'] = row.cells[1].innerHTML;
            selectedCells.push(obj_selected);
            obj_selected = {};
        });
        if(selectedCells.length == 0 ){
            document.getElementById("select-emp-err").style.display = "block";
            return;
        }
        $.ajax({
            url: 'daily-posting/setEmpIdsInSession',
            method: 'get',
            data: {'selectedCells': selectedCells},

            success:function(data){
            	window.open("daily-posting/print", "_blank", ",left=100,width=1200,height=800");
            },
            error: function(xhr, status, error) {

                $('.alert').show();
                $('.alert').addClass('error');
                $('.alert_msg').text(xhr.responseText);

            }

        });
    }
</script>
@endpush
@endsection
