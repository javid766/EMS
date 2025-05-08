(function($) {
	'use strict';
	document.getElementById('datein').valueAsDate = new Date();
    document.getElementById('dateout').valueAsDate = new Date();


var minVal , maxVal;
var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0 so need to add 1 to make it 1!
var yyyy = today.getFullYear();
if(dd<10){
  dd='0'+dd
} 
if(mm<10){
  mm='0'+mm
} 

minVal = yyyy+'-'+mm+'-'+'01';
maxVal = yyyy+'-'+mm+'-'+'31';

document.getElementById("datein").setAttribute("min", minVal);
document.getElementById("datein").setAttribute("max", maxVal);
document.getElementById("dateout").setAttribute("min", minVal);
document.getElementById("dateout").setAttribute("max", maxVal);

	$(document).ready(function()
	{
		var searchable = [];
		var selectable = []; 
		var datein = $('#datein').val();
		var dateout = $('#dateout').val();
		var dTableAttcode = $('#leaveEntryTable').DataTable({

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
				url:'leave-entry/fillgrid',
				type: "get",
				data: {'datein':datein, 'dateout':dateout }
			},
			columns: [
			
			{data:'employeename', name: 'employeename'},
			{data:'leavename', name: 'leavename'},
			{data:'datein',   name: 'datein'},
			{data:'dateout', name: 'dateout'},			
			{data:'status', name: 'status'},
			{data:'remarks', name: 'remarks'},
			{data:'action', name: 'action', orderable: false, searchable: false},
			

			],
			buttons: [
			{
				extend: 'copy',
				className: 'btn-sm btn-info',
				title: 'LeaveEntry',
				header: true,
				footer: true,
				exportOptions: {
	                        // columns: ':visible'
	                    }
	                },
	                {
	                	extend: 'csv',
	                	className: 'btn-sm btn-success',
	                	title: 'LeaveEntry',
	                	header: true,
	                	footer: true,
	                	exportOptions: {
	                        // columns: ':visible'
	                    }
	                },
	                {
	                	extend: 'excel',
	                	className: 'btn-sm btn-warning',
	                	title: 'LeaveEntry',
	                	header: true,
	                	footer: true,
	                	exportOptions: {
	                        // columns: ':visible',
	                    }
	                },
	                {
	                	extend: 'pdf',
	                	className: 'btn-sm btn-primary',
	                	title: 'LeaveEntry',
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
	                	title: 'LeaveEntry',
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
	$(document).on('click','#cancelBtn' ,function(){
		document.getElementById('datein').valueAsDate = new Date();
    	document.getElementById('dateout').valueAsDate = new Date();
	});



	//Delete Button
	$(document).on('click','#deleteRecord' ,function(){
		var id = $('#id').val();	
		window.location.href = 'leave-entry/delete/'+id;
	});

	//Edit Button
	$(document).on('click','#editBtn' ,function(){
		var id=$(this).attr("data");
		$('#id').val(id);
		$.ajax({
			url: 'leave-entry/fillform/'+id,
			type: 'get',
			dataType:"JSON",

			success:function(data){
				$('#employeeid').val(data["employeeid"]).change(); 
				$('#leavetypeid').val(data["leavetypeid"]).change();                           
                $('#datein').val(data["datein"]);
      			$('#dateout').val(data["dateout"]);
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