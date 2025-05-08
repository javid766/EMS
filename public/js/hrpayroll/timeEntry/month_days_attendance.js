(function($) {
	'use strict';
	$(document).ready(function()
	{
		//set Today's date as default
		var vdate = document.getElementById('datein').valueAsDate;
		if (vdate == null)
		{
			document.getElementById('datein').valueAsDate = new Date();
		}
	});

	$('#fillBtn').on('click', function(){
		var rowCount = $('#monthDaysAttTable >tbody >tr').length;
		if(rowCount>0){
			var yearMonth=$('#datein').val();
			var year = yearMonth.substring(0, 4);
			var month = yearMonth.substring(6, 7);
			var days = Math.round(((new Date(year, month))-(new Date(year, month-1)))/86400000);
			// console.log(days);
			 var ID=1;
            for (var i =1; i <=rowCount; i++) {
            	$("#attdays-"+i).val(days)
            }
		}
	});

	$('#searchBtn').on('click', function(){
		$('#monthdaysEntry').attr('action', "month-days/search").submit();

	});

	//Cancel Button
	$(document).on('click','#cancel', function(){	
		//set Today's date as default
	  document.getElementById('datein').valueAsDate = new Date();
		
	});

	$('#selectall').click(function(event) {
	  if (this.checked) {
	    $('.checkbox-entry:checkbox').prop('checked', true);
	  } else {
	    $('.checkbox-entry:checkbox').prop('checked', false);
	  }
	});
	//End Cancel Button

	$('select').select2();
})(jQuery);