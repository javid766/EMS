(function($) {
	'use strict';
	$(document).ready(function()
	{
		//set Today's date as default
		document.getElementById('vdate').valueAsDate = new Date();

		var searchable = [];
		var selectable = []; 

		var dTableAttcode = $('#localSaleTable').DataTable({

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
				url:'local-sale/fillgrid',
				type: "get"
			},
			columns: [

			{data:'employeename', name: 'employeename'},
			{data:'vdate', name: 'vdate'},
			{data:'invoiceno', name: 'invoiceno'},
			{data:'qty', name: 'qty'},
			{data:'amount', name: 'amount'},
			{data:'saletypename', name: 'saletypename'},			
			{data:'remarks', name: 'remarks'},
			{data:'action', name: 'action', orderable: false, searchable: false},
			

			],
			buttons: [
			{
				extend: 'copy',
				className: 'btn-sm btn-info',
				title: 'LocalSale',
				header: true,
				footer: true,
				exportOptions: {
	                        // columns: ':visible'
	                    }
	                },
	                {
	                	extend: 'csv',
	                	className: 'btn-sm btn-success',
	                	title: 'LocalSale',
	                	header: true,
	                	footer: true,
	                	exportOptions: {
	                        // columns: ':visible'
	                    }
	                },
	                {
	                	extend: 'excel',
	                	className: 'btn-sm btn-warning',
	                	title: 'LocalSale',
	                	header: true,
	                	footer: true,
	                	exportOptions: {
	                        // columns: ':visible',
	                    }
	                },
	                {
	                	extend: 'pdf',
	                	className: 'btn-sm btn-primary',
	                	title: 'LocalSale',
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
	                	title: 'LocalSale',
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

	//Cancel Button
	$(document).on('click','#cancelBtn', function(){	
		//set Today's date as default
		document.getElementById('vdate').valueAsDate = new Date();
	});
	//End Cancel Button

	
	//Delete Button
	$(document).on('click','#deleteRecord' ,function(){
		var id = $('#id').val();	
		window.location.href = 'local-sale/delete/'+id;
	});

	//Edit Button
	$(document).on('click','#editBtn' ,function(){
		var id=$(this).attr("data");
		$('#id').val(id);
		$.ajax({
			url: 'local-sale/fillform/'+id,
			type: 'get',
			dataType:"JSON",

			success:function(data){
				$('#employeeid').val(data["employeeid"]).change();
                $('#invoiceno').val(data["invoiceno"]);
      			$('#qty').val(data["qty"]);
      			$('#amount').val(data["amount"]);
                $('#saletypeid').val(data["saletypeid"]).change();
      			$('#vdate').val(data["vdate"]);
      			$('#remarks').val(data["remarks"]);
				jQuery('html,body').animate({scrollTop:0},500);

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