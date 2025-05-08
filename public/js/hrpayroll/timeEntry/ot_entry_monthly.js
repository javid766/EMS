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
	})
	if($('#deptid').val()){
		$("#deptid").prop('disabled',false);
		$("#alldepts").prop('checked',false);
	};
	$('#searchBtn').on('click', function(){
		$('#otEntryMonthly').attr('action', "ot-entry-monthly/search").submit();
	});

	//Cancel Button
	$(document).on('click','#cancelBtn', function(){	
		//set Today's date as default
		('#formArea').hide();
		document.getElementById('vdate').valueAsDate = new Date();
		document.getElementById("alldepts").checked = true;
		$("#deptid").prop("disabled", true);  
	});
	//End Cancel Button
	$('select').select2();
})(jQuery);