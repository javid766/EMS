(function($) {
	'use strict';
	//Account Category Data Table
	$(document).ready(function()
	{
		var searchable = [];
		var selectable = []; 

		var dTableDepartment = $('#tenatTable').DataTable({

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
				url:'tenant/fillgrid',
				type: "get"
			},
			columns: [

			{data:'vcode', name: 'vcode'},
			{data:'vname', name: 'vname'},
			{data:'company_nature', name: 'company_nature'},
			{data:'tlogin', name: 'tlogin'},
			{data:'action', name: 'action', orderable: false, searchable: false},
			

			],
			buttons: [
			{
				extend: 'copy',
				className: 'btn-sm btn-info',
				title: 'AccountGroup',
				header: true,
				footer: true,
				exportOptions: {
	                        // columns: ':visible'
	                    }
	                },
	                {
	                	extend: 'csv',
	                	className: 'btn-sm btn-success',
	                	title: 'AccountGroup',
	                	header: true,
	                	footer: true,
	                	exportOptions: {
	                        // columns: ':visible'
	                    }
	                },
	                {
	                	extend: 'excel',
	                	className: 'btn-sm btn-warning',
	                	title: 'AccountGroup',
	                	header: true,
	                	footer: true,
	                	exportOptions: {
	                        // columns: ':visible',
	                    }
	                },
	                {
	                	extend: 'pdf',
	                	className: 'btn-sm btn-primary',
	                	title: 'AccountGroup',
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
	                	title: 'AccountGroup',
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

		//End Department Data Table

	});

	//Edit Button
	$(document).on('click','#editBtn' ,function(){
		var id=$(this).attr("data");
		$('#id').val(id);
		$.ajax({
			url: 'tenant/fillform/'+id,
			type: 'get',
			dataType:"JSON",

			success:function(data){
				$('#vname').val(data["vname"]);
				$('#vcode').val(data["vcode"]);
				$('#company_nature').val(data["company_nature"]);
				$('#countryname').val(data["countryname"]);
				$('#currnecyname').val(data["currencyname"]);
				$('#url').val(data["url"]);
				$('#tlogin').val(data["tlogin"]);

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
	//Delete Record
	$(document).on('click','#deleteRecord' ,function(){
		var id = $('#id').val();	
		window.location.href = 'tenant/delete/'+id;
	});
	//End Delete Record
	
	//End Save Button

	$('select').select2();
})(jQuery);