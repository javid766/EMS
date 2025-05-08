(function($) {
	'use strict';
	$(document).ready(function()
	{
		var datetofrom = document.getElementById('datetofrom').valueAsDate;
		if (datetofrom == null && !$('#datetofrom').prop('readonly'))
		{
			document.getElementById('datetofrom').valueAsDate = new Date();
		}
	});
	$('#alldepts').change(function() {
		$("#deptid").prop('disabled', ($(this).is(":checked") == false ? false : true));
	});
	
	$('#saveBtn').on('click', function(){
		//$('.table_entry').show();
		$('#attEntry').attr('action', "attendance-entry/save").submit();

	});
	
	if($('#deptid').val()){
		$("#deptid").prop('disabled',false);
		$("#alldepts").prop('checked',false);
	}

	$('#selectall').click(function(event) {
	  if (this.checked) {
	    $('.checkbox-entry:checkbox').prop('checked', true);
	  } else {
	    $('.checkbox-entry:checkbox').prop('checked', false);
	  }
	});

	$('select').select2();
})(jQuery);