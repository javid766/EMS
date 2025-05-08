(function($) {
	'use strict';
	$('#alldepts').change(function() {
		$("#deptid").prop('disabled', ($(this).is(":checked") == false ? false : true));
	});
	$(document).ready(function()
	{
		var vdate = document.getElementById('vdate').valueAsDate;
		if (vdate == null)
		{
			document.getElementById('vdate').valueAsDate = new Date();
		}
	});

	$('#searchBtn').on('click', function(){
		$('#otEntryDaily').attr('action', "daily-manual-ot/search").submit();

	});
	if($('#deptid').val()){
		$("#deptid").prop('disabled',false);
		$("#alldepts").prop('checked',false);
	}
	//Cancel Button
	$(document).on('click','#cancelBtn', function(){	
		//set Today's date as default
		document.getElementById('vdate').valueAsDate = new Date();
		document.getElementById("alldepts").checked = true;
		$("#deptid").prop("disabled", true);  
	});
	$('select').select2();
})(jQuery);