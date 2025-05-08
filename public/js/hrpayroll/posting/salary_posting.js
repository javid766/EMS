(function($) {
	'use strict';
	document.getElementById('vdate').valueAsDate = new Date();

		// on change of allemployees checkbox
	 $('#allemps').change(function() {
	    if ($(this).is(':checked')) {
	        // disable the dropdown:
	        $('#employeeid').attr('disabled', 'disabled');
	    } else {
	        $('#employeeid').removeAttr('disabled');
	    }
 	 });


	$('select').select2();
})(jQuery);