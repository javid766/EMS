(function($) {
	'use strict';
	$(document).ready(function()
	{
		var vdate = document.getElementById('vdate').valueAsDate;
		if (vdate == null)
		{
			if (!$('#vdate').prop('readonly')) {

				document.getElementById('vdate').valueAsDate = new Date();
			}
		}
	});

	$('#searchBtn').on('click', function(){
		var deptid = $('#deptid').find(":selected").val();
		var employeeid = $('#empid').find(":selected").val();
		$('#changeAtt').attr('action', "change-attendence/search").submit();
		// if(deptid || employeeid){
		// 	$('#changeAtt').attr('action', "change-attendence/search").submit();
		// }
		// else{
		// 	validateRosterForm();
		// }
		

	});
	$('select').select2();
})(jQuery);