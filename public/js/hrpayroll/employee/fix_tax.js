(function($) {
	'use strict';
	// Data Table
	$(document).ready(function()
	{
		//set Today's date as default
		document.getElementById('vdate').valueAsDate = new Date();
		document.getElementById('vdatefrom').valueAsDate = new Date();

		var searchable = [];
		var selectable = []; 

		var dTableDepartment = $('#empFixTaxTable').DataTable({

			order: [],
			lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			processing: true,
			responsive: false,
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
				url:'fix-tax/fillgrid',
				type: "get"
			},
			columns: [
			{data:'employeename', name: 'employeename'},
			{data:'amount', name: 'amount'},
			{data:'vdate', name: 'vdate'},
			{data:'vdatefrom', name: 'vdatefrom'},
			{data:'action', name: 'action', orderable: false, searchable: false},
			

			],
			buttons: [
			{
				extend: 'copy',
				className: 'btn-sm btn-info',
				title: 'FixTax',
				header: true,
				footer: true,
				exportOptions: {
	                        // columns: ':visible'
	                    }
	                },
	                {
	                	extend: 'csv',
	                	className: 'btn-sm btn-success',
	                	title: 'FixTax',
	                	header: true,
	                	footer: true,
	                	exportOptions: {
	                        // columns: ':visible'
	                    }
	                },
	                {
	                	extend: 'excel',
	                	className: 'btn-sm btn-warning',
	                	title: 'FixTax',
	                	header: true,
	                	footer: true,
	                	exportOptions: {
	                        // columns: ':visible',
	                    }
	                },
	                {
	                	extend: 'pdf',
	                	className: 'btn-sm btn-primary',
	                	title: 'FixTax',
	                	pageSize: 'A2',
	                	header: true,
	                	footer: true,
	                	exportOptions: {
	                        // columns: ':visible'
	                    }
	                },
	                {
	                	extend: 'print',
	                	className: 'btn-sm btn-default',
	                	title: 'FixTax',
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

		//End Data Table

	});

 	//Delete Button
	$(document).on('click','#deleteRecord' ,function(){
		var id = $('#id').val();	
		window.location.href = 'fix-tax/delete/'+id;
	});

	//Cancel Button
	$(document).on('click','#cancelBtn', function(){	
		//set Today's date as default
		document.getElementById('vdate').valueAsDate = new Date();
		document.getElementById('vdatefrom').valueAsDate = new Date();
	});
	//End Cancel Button

	//Edit Button
	$(document).on('click','#editBtn' ,function(){
		var id = $(this).attr("data");
		$('#id').val(id);
		
		$.ajax({
                url: 'fix-tax/fillform/'+id,
                type: 'get',
                dataType:"JSON",

                success:function(data){
                	$('#vdate').val(data["vdate"]);
                	$('#vdatefrom').val(data["vdatefrom"]);
                	$('#employeeid').val(data["employeeid"]).change();
      				$('#amount').val(data["amount"]);
                	$('html,body').animate({scrollTop:0},500);
                },
                error: function(xhr, status, error) {

                    $('.alert').show();
                    $('.alert').addClass('error');
                    $('.alert_msg').text(xhr.responseText);

                }
  
            })
		
	});
	//End Edit Button

	$('select').select2();
})(jQuery);