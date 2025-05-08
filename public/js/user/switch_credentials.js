(function($) {
	'use strict';
	
	$(document).ready(function(){

		$('#company').change(function(){
			//console.log();
			$("#companyname").val($("#company option:selected").text());

			var companyid = $(this).val();
			var financialYearOptions = "";

			if(companyid != "") {

				$.ajax({
					url: 'switch-credentials/financialyears',
					method: "POST",
					data: {'companyid': companyid, '_token' : $("input[name='_token']").val()},

					success: function(response) {

						var financialYears = response;

						console.log(financialYears);

						if(financialYears.length != 0) {

							financialYearOptions += "<option value=''>--- Select ---</option>";

							for(var index in financialYears) {

								financialYearOptions += "<option value='"+index+"'>"+ financialYears[index]+"</option>";
							}
						}
						
						$('#financialyear').empty();
						$('#financialyear').append(financialYearOptions);
					}
				});
			}
		});

		$('#project').change(function(){
			//console.log();
			$("#projectname").val($("#project option:selected").text());
		});
	});

})(jQuery);