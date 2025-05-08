(function($) {
	'use strict'; 
	// Data Table
	$(document).ready(function()
	{
		var vdate = document.getElementById('vdatein').valueAsDate;
		if (vdate == null)
		{
			document.getElementById('vdatein').valueAsDate = new Date();
		}
	});
	$('select').select2();
	$('#fetchbtn').on('click', function(){
		$('#empLoanDeduction').show();
		$('#loanDeductionForm').attr('action', "loan-deduction/search").submit();

		//window.location = "att-entry/search";
	});
})(jQuery);