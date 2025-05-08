(function($) {
	'use strict';
	//set Today's date as default
	var date = new Date();
	document.getElementById('dateto').valueAsDate = new Date(date.getFullYear(), date.getMonth() + 1, 1);
	$(document).on('click','#fillBtn' ,function(){
		
		var searchable = [];
		var selectable = []; 
		var employeeid = $('#employeeid').val();
		var etypeid = $('#etypeid').val();
		var locationid = $('#locationid').val();
		var deptid = $('#deptid').val();
		var datefrom = $('#datefrom').val();
		var dateto = $('#dateto').val();
		var attfilter     = $("input[type='radio'][name='attfilter']:checked").val();
		if (attfilter == 'attcard'){

			$.ajax({
				url: 'monthy-attendance-report/setsession/',
				method: "GET",
				data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'datefrom':datefrom,'dateto':dateto,'attfilter':attfilter},

				success:function(data){

					window.open("monthy-attendance-report/atendancecard/", '_blank');
				
				},
				error: function(xhr, status, error) {
					$('.alert').show();
					$('.alert').addClass('error');
					$('.alert_msg').text(xhr.responseText);
				}
				
			});
				
		}
		if (attfilter == 'absenteelist'){
			document.getElementById("attabsenteelisttable").style.display="block";
			document.getElementById("attendancecardtable").style.display="none";
			document.getElementById("attMonthlySummarytable").style.display="none";
			document.getElementById("attendancelogstable").style.display="none";
			document.getElementById("attLeaveListtable").style.display="none";	
			var upostedreporttable = $('#absenteelisttable').DataTable({
				order: [],
				lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
				processing: true,
				responsive: true,
				serverSide: true,
				processing: true,
				paging: true,
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
					url:'monthy-attendence-report/fillgrid/',
					data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'datefrom':datefrom,'dateto':dateto,'attfilter':attfilter
				},
				type: "get",
			},
			destroy:true,
			columns: [
			{data:'employeename', name: 'employeename'},
			{data:'department', name: 'department'},
			{data:'designation', name: 'designation'},
			{data:'starttime', name: 'starttime'},	
			{data:'doj', name: 'doj'},				
			{data:'ablist', name: 'ablist'}
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
		if (attfilter == 'attlogs'){
			document.getElementById("attabsenteelisttable").style.display="none";
			document.getElementById("attendancecardtable").style.display="none";
			document.getElementById("attMonthlySummarytable").style.display="none";
			document.getElementById("attLeaveListtable").style.display="none";
			document.getElementById("attendancelogstable").style.display="block";
			var groupColumn = 0;
			var attlogstable = $('#attlogstable').DataTable({				
				columnDefs: [{ "visible": false, "targets": groupColumn } ,
							 { 'targets': 1,
								render: function (data, type) {
						             if (data !== null) {
						                var wrapper = moment(new Date(data));
						                return wrapper.format("DD-MMM-YYYY");
						             }
				        			 }
				        		}
				],
       			order: [[ groupColumn, 'asc' ]],
				lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
				processing: true,
				responsive: true,
				serverSide: true,
				processing: true,
				paging: true,
				pageLength: 100,
				pagingType:'numbers',
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
					url:'monthy-attendence-report/fillgrid/',
					data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'datefrom':datefrom,'dateto':dateto,'attfilter':attfilter
				},
				type: "get",
			},
			destroy:true,
			columns: [
				{data:'empdetails', name: 'empdetails'},
				{data:'datein', name: 'datein'},
				{data:'starttime', name: 'starttime'},
				{data:'remarks', name: 'remarks'},			
			],

			drawCallback: function ( settings ) {
	            var api = this.api();
	            var rows = api.rows( {page:'current'} ).nodes();
	            var last=null;	 
	            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
	                if ( last !== group ) {
	                    $(rows).eq( i ).before(
	                        '<tr class="rpt-group"><td colspan="7" class="rpt-group-content">'+group+'</td></tr>'
	                    );
	 
	                    last = group;
	                }
	            } );
	        }
		});
		}
		if (attfilter == 'leavelist'){
			document.getElementById("attabsenteelisttable").style.display="none";
			document.getElementById("attendancecardtable").style.display="none";
			document.getElementById("attMonthlySummarytable").style.display="none";
			document.getElementById("attendancelogstable").style.display="none";
			document.getElementById("attLeaveListtable").style.display="block";
			var leavelisttable = $('#leavelisttable').DataTable({
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
					url:'monthy-attendence-report/fillgrid/',
					data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'datefrom':datefrom,'dateto':dateto,'attfilter':attfilter
				},
				type: "get",
			},
			destroy:true,
			columns: [
			{data:'employeename', name: 'employeename'},
			{data:'department', name: 'department'},
			{data:'designation', name: 'designation'},
			{data:'datein', name: 'datein'},
			{data:'dateout', name: 'dateout'},
			{data:'leavename', name: 'leavename'},
			{data:'remarks', name: 'remarks'},				
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
		if (attfilter == 'attmsummary'){
			document.getElementById("attabsenteelisttable").style.display="none";
			document.getElementById("attendancecardtable").style.display="none";
			document.getElementById("attLeaveListtable").style.display="none";
			document.getElementById("attendancelogstable").style.display="none";
			document.getElementById("attMonthlySummarytable").style.display="block";
			var leavelisttable = $('#monthlySummarytable').DataTable({
				order: [],
				lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
				processing: true,
				responsive: true,
				serverSide: true,
				processing: true,
				paging: true,
				pageLength: 200,
				pagingType:'numbers',
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
					url:'monthy-attendence-report/fillgrid/',
					data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'datefrom':datefrom,'dateto':dateto,'attfilter':attfilter
				},
				type: "get",
			},
			destroy:true,
			columns: [
				{data:'employeename', name: 'employeename', width:'8%'},
				{data:'department', name: 'department', width:'8%'},
				{data:'designation', name: 'designation', width:'8%'},
				{data:'monthdays', name: 'monthdays', width:'4%'},
				{data:'workingdays', name: 'workingdays', width:'5%'},
				{data:'overtime', name: 'overtime', width:'3%'},
				{data:'ppdays', name: 'ppdays', width:'5%'},
				{data:'hddays', name: 'hddays', width:'4%'},
				{data:'ghdays', name: 'ghdays', width:'6%'},
				{data:'fhdays', name: 'fhdays', width:'6%'},
				{data:'wedays', name: 'wedays', width:'4%'},
				{data:'annual', name: 'annual', width:'4%'},
				{data:'casual', name: 'casual', width:'4%'},
				{data:'sick', name: 'sick', width:'4%'},
				{data:'mldays', name: 'mldays', width:'4%'},
				{data:'cpl', name: 'cpl', width:'4%'},
				{data:'abdays', name: 'abdays', width:'5%'},
				{data:'netdays', name: 'netdays', width:'5%'},
				{data:'deductiondays', name: 'deductiondays', width:'5%'}			
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
	});
$('select').select2();

})(jQuery);