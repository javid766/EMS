(function($) {
	'use strict';
	//Account Type Data Table
	
	$(document).ready(function()
	{
		var searchable = [];
		var selectable = []; 
		document.getElementById('vdate').valueAsDate = new Date();
		document.getElementById('chequedate').valueAsDate = new Date();
		var vdate = $('#vdate').val();
		var dTableDepartment = $('#closingMonthChequeTable').DataTable({

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
				url:'closing-month-cheque/fillgrid',
				data: {'vdate': vdate },
				type: "get"
			},
			columns: [
			{data:'employeename', name: 'employeename'},
			{data:'vtype', name: 'vtype'},
			{data:'bankname', name: 'bankname'},
			{data:'chequeno', name: 'chequeno'},
			{data:'chequedate', name: 'chequedate'},
			{data:'chequeamount', name: 'chequeamount'},
			{data:'remarks', name: 'remarks'},
			{data:'action', name: 'action', orderable: false, searchable: false},
			],
			buttons: [
			{
				extend: 'copy',
				className: 'btn-sm btn-info',
				title: 'EmployeeClosingMonthCheque ',
				header: true,
				footer: true,
				exportOptions: {
	                        // columns: ':visible'
	                    }
	                },
	                {
	                	extend: 'csv',
	                	className: 'btn-sm btn-success',
	                	title: 'EmployeeClosingMonthCheque ',
	                	header: true,
	                	footer: true,
	                	exportOptions: {
	                        // columns: ':visible'
	                    }
	                },
	                {
	                	extend: 'excel',
	                	className: 'btn-sm btn-warning',
	                	title: 'EmployeeClosingMonthCheque ',
	                	header: true,
	                	footer: true,
	                	exportOptions: {
	                        // columns: ':visible',
	                    }
	                },
	                {
	                	extend: 'pdf',
	                	className: 'btn-sm btn-primary',
	                	title: 'EmployeeClosingMonthCheque ',
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
	                	title: 'EmployeeClosingMonthCheque ',
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

		//End Account Type Data Table

	});

	//Cancel Button
	$(document).on('click','#cancelBtn', function(){	
		document.getElementById('vdate').valueAsDate = new Date();
		document.getElementById('chequedate').valueAsDate = new Date();
		
	});
	//End Cancel Button

 	//Delete Button
	$(document).on('click','#deleteRecord' ,function(){
		var id = $('#id').val();	
		window.location.href = 'closing-month-cheque/delete/'+id;
	});

	//Edit Button
	$(document).on('click','#editBtn' ,function(){
		var data=$(this).attr("data");
		data = data.split(","); 
		var id = data[0];
		var vdate = data[1];
		var employeeid = data[2];
		$('#id').val(id);
		
		$.ajax({
                url: 'closing-month-cheque/fillform/'+id,
                type: 'get',
                data: {'employeeid':employeeid, 'vdate':vdate},
                dataType:"JSON",

                success:function(data){
                	console.log(data);
                	$('#employeeid').val(data["employeeid"]).change();
                	$('#vdate').val(data["vdate"]);
                	$('#chequeno').val(data["chequeno"]);
                	$('#chequedate').val(data["chequedate"]);
                	$('#chequeamount').val(data["chequeamount"]);
                	$('#vtype').val(data["vtype"]).change();
                	$('#bankid').val(data["bankid"]).change();                	
      				$('#remarks').val(data["remarks"]);
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

	//Save Button
	$(document).on('click','#saveBtn', function(){	
		$('form').submit();
	});

	$('select').select2();
})(jQuery);