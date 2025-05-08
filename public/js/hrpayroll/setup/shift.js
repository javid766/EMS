(function($) {
	'use strict';
	//Shift Data Table
	$(document).ready(function()
	{
		var searchable = [];
		var selectable = []; 
		var date = new Date();
		var currentTime = date.getHours() + ':' + (date.getMinutes()<10?'0':'') + date.getMinutes();
		document.getElementById('timein').value = currentTime;
		document.getElementById('timeout').value = currentTime;
		document.getElementById('workinghrs').value = currentTime;
		document.getElementById('resttimefrom').value = currentTime;
		document.getElementById('resttimeto').value = currentTime;
		document.getElementById('relaxtime').value = currentTime;
		document.getElementById('minatttime').value = currentTime;
		document.getElementById('minhdtime').value = currentTime;
		var dTableDepartment = $('#shiftTable').DataTable({

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
				url:'shift/fillgrid',
				type: "get"
			},
			columns: [

			{data:'vcode', name: 'vcode'},
			{data:'vname', name: 'vname'},
			{data:'timein', name:'timein'},
			{data:'timeout', name:'timeout'},
			{data:'workinghrs', name:'workinghrs'},
			{data:'action', name: 'action', orderable: false, searchable: false},
			

			],
			buttons: [
			{
				extend: 'copy',
				className: 'btn-sm btn-info',
				title: 'Shift',
				header: true,
				footer: true,
				exportOptions: {
	                        // columns: ':visible'
	                    }
	                },
	                {
	                	extend: 'csv',
	                	className: 'btn-sm btn-success',
	                	title: 'Shift',
	                	header: true,
	                	footer: true,
	                	exportOptions: {
	                        // columns: ':visible'
	                    }
	                },
	                {
	                	extend: 'excel',
	                	className: 'btn-sm btn-warning',
	                	title: 'Shift',
	                	header: true,
	                	footer: true,
	                	exportOptions: {
	                        // columns: ':visible',
	                    }
	                },
	                {
	                	extend: 'pdf',
	                	className: 'btn-sm btn-primary',
	                	title: 'Shift',
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
	                	title: 'Shift',
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

		//End Shift Data Table

	});
	
	//Delete Button
	$(document).on('click','#deleteRecord' ,function(){
		var id = $('#id').val();	
		window.location.href = 'shift/delete/'+id;
	});


	//Edit Button
	$(document).on('click','#editBtn' ,function(){
		var id=$(this).attr("data");
		$('#id').val(id);
		
		$.ajax({
                url: 'shift/fillform/'+id,
                type: 'get',
                dataType:"JSON",

                success:function(data){
                	$('#vcode').val(data["vcode"]);
                	$('#vname').val(data["vname"]);
                	$('#timein').val(data["timein"]);
                	$('#timeout').val(data["timeout"]);
                	$('#resttimefrom').val(data["resttimefrom"]);
                	$('#resttimeto').val(data["resttimeto"]);
                	$('#workinghrs').val(data["workinghrs"]);
                	$('#relaxtime').val(data["relaxtime"]);
                	$('#minatttime').val(data["minatttime"]);
                	$('#minhdtime').val(data["minhdtime"]);
                    data['isroster'] == 1 ? $("#isroster").prop('checked', true): 
                 	$("#isroster").prop('checked', false);
                 	data['issecurity'] == 1 ? $("#issecurity").prop('checked', true): 
                	$("#issecurity").prop('checked', false);
                	data['isactive'] == 1 ? $("#isactive").prop('checked', true): 
                	$("#isactive").prop('checked', false);
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