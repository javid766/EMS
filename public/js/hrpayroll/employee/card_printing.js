(function($) {
	'use strict';
	
	// on change of dept checkbox
	 $('#alldepts').change(function() {
	    if ($(this).is(':checked')) {
	        // disable the dropdown:
	        $('#deptid').attr('disabled', 'disabled');
	    } else {
	        $('#deptid').removeAttr('disabled');
	    }
 	 });
	 // on change of dept drop down

	//Fill Button
	$(document).on('click','#fillBtn' ,function(){
		var searchable = [];
		var selectable = []; 

		var dTable  = $('#cardPrintingTable');
		var deptid  = $('#deptid').find(":selected").val();
		var etypeid = $('#etypeid').find(":selected").val();
		var locationid = $('#locationid').find(":selected").val();
		var alldepts= $('#alldepts').is(":checked");
		if (deptid == "" && alldepts == false) {
			selectDept();
		}
		if(etypeid && (deptid || alldepts == true)){
			document.getElementById("cardPrinting").style.display = "block";
			dTable.DataTable({
				order: [],
				lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
				processing: true,
				responsive: true,
				serverSide: true,
				processing: true,
				paging: false,
				language: {
				processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>',
				search: "",
				searchPlaceholder: "Search here..."
				},
				scroller: {
					loadingIndicator: false
				},
				dom: "<'row'<'grid-actions col-sm-6'B><'col-sm-6'f>>tipr",
				ajax: {
					url:'card-printing/fill',
					type: "get",
					data: { 'deptid' : deptid, 
							'etypeid' : etypeid, 
							'locationid': locationid,
							'alldepts': alldepts
						},
				},
				destroy:true,
				columns: [
					{data:'checkboxes', name: 'checkboxes',orderable: false},
					{data:'id', name: 'id'},
					{data:'empcode', name: 'empcode'},
					{data:'employeename', name: 'employeename'},
					{data:'department', name: 'department'},	

				],
				columnDefs: [
				    { targets: [ 1 ],
				      className: "hide_column"
				    }
			  	],
		        initComplete: function () {
		                	var api =  this.api();
		                	api.columns(searchable).every(function () {
		                		var column = this;
		                		var input = document.createElement("input");
		                		input.setAttribute('placeholder', $(column.header()).text());
		                		input.setAttribute('style', 'width: 140px; height:25px; border:1px solid whitesmoke;');

		                		$(input).appendTo($(column.header()).empty())
		                		.on('keyup', function () {
		                			column.search($(this).val(), false, false, true).draw();
		                		});

		                		$('input', this.column(column).header()).on('click', function(e) {
		                			e.stopPropagation();
		                		});
		                	});

		                	api.columns(selectable).every( function (i, x) {
		                		var column = this;

		                		var select = $('<select style="width: 140px; height:25px; border:1px solid whitesmoke; font-size: 12px; font-weight:bold;"><option value="">'+$(column.header()).text()+'</option></select>')
		                		.appendTo($(column.header()).empty())
		                		.on('change', function(e){
		                			var val = $.fn.dataTable.util.escapeRegex(
		                				$(this).val()
		                				);
		                			column.search(val ? '^'+val+'$' : '', true, false ).draw();
		                			e.stopPropagation();
		                		});

		                		$.each(dropdownList[i], function(j, v) {
		                			select.append('<option value="'+v+'">'+v+'</option>')
		                		});
		                	});
		                }

		            });
		}
		else{
			document.getElementById("cardPrinting").style.display = "none";
		}
	});


	$("#select-all-checkboxes").click(function(){
	    $('#cardPrintingTable input:checkbox').not(this).prop('checked', this.checked);
	});
	$('#cardCancelBtn').click(function() {
        location.reload();
    });
	
	$('select').select2();
})(jQuery);