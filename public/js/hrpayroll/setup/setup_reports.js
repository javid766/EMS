(function($) {
	'use strict';

	$(document).on('click','#searchBtn' ,function(){
		if($("input[type='radio'].setupReportsRadio").is(':checked')) {
			var radioBtnID = $("input[type='radio'].setupReportsRadio:checked").val();
			var searchable = [];
			var selectable = []; 

			//for dept group list
			if (radioBtnID == 2){
				document.getElementById("setupReportsGrid").style.display="block";
				document.getElementById("setupReportsFixTax").style.display="none";
				var dTableDepartment = $('#setupReportsTable').DataTable({
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
					url:'setup-report/fillgrid/'+radioBtnID,
					type: "get"
				},
				destroy:true,
				columns: [
				{data:'vcode', name: 'vcode'},
				{data:'VName', name: 'VName'},
				],
				buttons: [
				{
					extend: 'copy',
					className: 'btn-sm btn-info',
					title: 'SetupReport',
					header: true,
					footer: false,
					exportOptions: {
		                        // columns: ':visible'
		                    }
		                },
		                {
		                	extend: 'csv',
		                	className: 'btn-sm btn-success',
		                	title: 'SetupReport',
		                	header: true,
		                	footer: false,
		                	exportOptions: {
		                        // columns: ':visible'
		                    }
		                },
		                {
		                	extend: 'excel',
		                	className: 'btn-sm btn-warning',
		                	title: 'SetupReport',
		                	header: true,
		                	footer: false,
		                	exportOptions: {
		                        // columns: ':visible',
		                    }
		                },
		                {
		                	extend: 'pdf',
		                	className: 'btn-sm btn-primary',
		                	title: 'SetupReport',
		                	pageSize: 'A2',
		                	header: true,
		                	footer: false,
		                	exportOptions: {
		                        // columns: ':visible'
		                    }
		                },
		                {
		                	extend: 'print',
		                	className: 'btn-sm btn-default',
		                	title: 'SetupReport',
		                    // orientation:'landscape',
		                    pageSize: 'A2',
		                    header: true,
		                    footer: false,
		                    orientation: 'landscape',
		                    exportOptions: {
		                        // columns: ':visible',
		                        stripHtml: false
		                    }
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
			// for fix taxslab
			if (radioBtnID== 6){
				document.getElementById("setupReportsFixTax").style.display="block";
				document.getElementById("setupReportsGrid").style.display="none";
				var dTableDepartment = $('#setupReportsTableFixtax').DataTable({
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
					url:'setup-report/fillgrid/'+radioBtnID,
					type: "get"
				},
				destroy:true,
				columns: [
				{data:'amountfrom', name: 'amountfrom'},
				{data:'amountto', name: 'amountto'},
				{data:'fixtax', name: 'fixtax'},
				{data:'percentage', name: 'percentage'},
				],
				buttons: [
				{
					extend: 'copy',
					className: 'btn-sm btn-info',
					title: 'SetupReport',
					header: true,
					footer: false,
					exportOptions: {
		                        // columns: ':visible'
		                    }
		                },
		                {
		                	extend: 'csv',
		                	className: 'btn-sm btn-success',
		                	title: 'SetupReport',
		                	header: true,
		                	footer: false,
		                	exportOptions: {
		                        // columns: ':visible'
		                    }
		                },
		                {
		                	extend: 'excel',
		                	className: 'btn-sm btn-warning',
		                	title: 'SetupReport',
		                	header: true,
		                	footer: false,
		                	exportOptions: {
		                        // columns: ':visible',
		                    }
		                },
		                {
		                	extend: 'pdf',
		                	className: 'btn-sm btn-primary',
		                	title: 'SetupReport',
		                	pageSize: 'A2',
		                	header: true,
		                	footer: false,
		                	exportOptions: {
		                        // columns: ':visible'
		                    }
		                },
		                {
		                	extend: 'print',
		                	className: 'btn-sm btn-default',
		                	title: 'SetupReport',
		                    // orientation:'landscape',
		                    pageSize: 'A2',
		                    header: true,
		                    footer: false,
		                    orientation: 'landscape',
		                    exportOptions: {
		                        // columns: ':visible',
		                        stripHtml: false
		                    }
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
			

			else if(radioBtnID== 1 || radioBtnID== 3 || radioBtnID== 4 || radioBtnID== 5){
				document.getElementById("setupReportsGrid").style.display="block";
				document.getElementById("setupReportsFixTax").style.display="none";
				var dTableDepartment = $('#setupReportsTable').DataTable({

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
					url:'setup-report/fillgrid/'+radioBtnID,
					type: "get"
				},
				destroy:true,
				columns: [
				{data:'vcode', name: 'vcode'},
				{data:'vname', name: 'vname'},
				],
				buttons: [
				{
					extend: 'copy',
					className: 'btn-sm btn-info',
					title: 'SetupReport',
					header: true,
					footer: false,
					exportOptions: {
		                        // columns: ':visible'
		                    }
		                },
		                {
		                	extend: 'csv',
		                	className: 'btn-sm btn-success',
		                	title: 'SetupReport',
		                	header: true,
		                	footer: false,
		                	exportOptions: {
		                        // columns: ':visible'
		                    }
		                },
		                {
		                	extend: 'excel',
		                	className: 'btn-sm btn-warning',
		                	title: 'SetupReport',
		                	header: true,
		                	footer: false,
		                	exportOptions: {
		                        // columns: ':visible',
		                    }
		                },
		                {
		                	extend: 'pdf',
		                	className: 'btn-sm btn-primary',
		                	title: 'SetupReport',
		                	pageSize: 'A2',
		                	header: true,
		                	footer: false,
		                	exportOptions: {
		                        // columns: ':visible'
		                    }
		                },
		                {
		                	extend: 'print',
		                	className: 'btn-sm btn-default',
		                	title: 'SetupReport',
		                    // orientation:'landscape',
		                    pageSize: 'A2',
		                    header: true,
		                    footer: false,
		                    orientation: 'landscape',
		                    exportOptions: {
		                        // columns: ':visible',
		                        stripHtml: false
		                    }
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
			}

	});

	
	//Cancel Button
	$(document).on('click','#cancelBtn' ,function(){
		$('.setupReportsRadio').prop('checked', false);
		document.getElementById("setupReportsGrid").style.display="none";
		document.getElementById("setupReportsFixTax").style.display="none";
	});
	//End Cancel Button

	$('select').select2();

})(jQuery);