(function($) {
	'use strict';

	$(document).ready(function()
	{
		//set Today's date as default
		var vdate = document.getElementById('vdate').valueAsDate;
		if (vdate == null){
			document.getElementById('vdate').valueAsDate = new Date();
		}

	});

	$('#searchBtn').on('click', function(){
		var deptid = $('#deptid').find(":selected").val();
		var employeeid = $('#employeeid').find(":selected").val();
		if(deptid || employeeid){
			$('#rosterEntry').attr('action', "roster-entry/search").submit();
		}
		else{
			validateRosterForm();
		}

	});

	//Cancel Button
	$(document).on('click','#cancelBtn', function(){	
		//set Today's date as default
		document.getElementById('vdate').valueAsDate = new Date();
		
	});
	//End Cancel Button

	$('select').select2();
})(jQuery);