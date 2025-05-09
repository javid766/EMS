(function($) {
	'use strict';
	//set Today's date as default
	document.getElementById('datein').valueAsDate = new Date();
	$(document).on('click','#fillBtn' ,function(){
		var searchable = [];
		var selectable = []; 
		var employeeid = $('#employeeid').val();
		var etypeid = $('#etypeid').val();
		var locationid = $('#locationid').val();
		var token  = $('#token').val();
		var deptid = $('#deptid').val();
		var deptid = $('#deptid').val();
		var datein = $('#datein').val();
		var attfilter = $("input[type='radio'][name='attfilter']:checked").val();
		if (attfilter == 'salaryslippdf') {
			if (employeeid == '') {
				validateEmloyeeSalarySlip();
			}
			else if (datein == '') {
				validateDateSalarySlip();
			}
			else{
				$.ajax({
				url: 'monthy-salary-report/setsession/',
				method: "GET",
				data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'datein':datein},

				success:function(data){

					window.open("monthy-salary-report/salaryslip/", '_blank');
				
				},
				error: function(xhr, status, error) {
					$('.alert').show();
					$('.alert').addClass('error');
					$('.alert_msg').text(xhr.responseText);
				}
				
			})
				
			}
		}
		if (attfilter == 'salsheetcash' || attfilter == 'salsheetcheque' || attfilter == 'salsheetcomplete' || attfilter == 'finalsettlement'){
			document.getElementById("attMonthlySaltable").style.display = "block";
			document.getElementById("attMonthlySalSumtable").style.display = "none";
			document.getElementById("attEmpSalHistorytable").style.display = "none";
			document.getElementById("attAdvanceDeductionSheettable").style.display = "none";
			document.getElementById("attAdvanceDeductionSummarytable").style.display = "none";

			var groupColumn = 2;
			var table = $('#monthlySaltable').DataTable({
				columnDefs: [{ "visible": false, "targets": groupColumn }],
       			order: [[ groupColumn, 'asc' ]],
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
					url:'monthy-salary-report/fillgrid',
					data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'datein':datein,'attfilter':attfilter, '_token': token
				},
				type: 'POST',
				dataType:"JSON",
			},
			destroy:true,
			columns: [
			{data:'empcode', name: 'empcode',width:'6%'},
			{data:'employeename', name: 'employeename',width:'15%'},
			{data:'department', name: 'department'},
			{data:'designation', name: 'designation',width:'15%'},
			{data:'salary', name: 'salary',width:'5%'},
			{data:'workingdays', name: 'workingdays',width:'6%'},
			{data:'salarydays', name: 'salarydays',width:'5%'},
			{data:'overtime', name: 'overtime'},
			{data:'allowance1', name: 'allowance1'},			
			{data:'calculatedsalary', name: 'calculatedsalary',width:'6%'},
			{data:'incometax', name: 'incometax',width:'5%'},
			{data:'advance', name: 'advance'},
			{data:'loan', name: 'loan'},		
			{data:'deduction1', name: 'deduction1'},		
			{data:'netsalary', name: 'netsalary',width:'5%'},
			],


			drawCallback: function ( settings ) {
	            var api = this.api();
	            var rows = api.rows( {page:'current'} ).nodes();
	            var last=null;
	 
	            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
	                if ( last !== group ) {
	                    $(rows).eq( i ).before(
	                        '<tr class="dept-group"><td colspan="15">'+group+'</td></tr>'
	                    );
	 
	                    last = group;
	                }
	            } );
	        }

		});
	}

	if(attfilter == 'salarysummarycash'|| attfilter == 'salarysummarycheque'
	    || attfilter == 'summarycomplete' || attfilter == 'finalsettlementsummary'){
			document.getElementById("attMonthlySaltable").style.display = "none";
			document.getElementById("attMonthlySalSumtable").style.display = "block";
			document.getElementById("attEmpSalHistorytable").style.display = "none";
			document.getElementById("attAdvanceDeductionSheettable").style.display = "none";
			document.getElementById("attAdvanceDeductionSummarytable").style.display = "none";
			var table = $('#monthlySalSumtable').DataTable({
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
					url:'monthy-salary-report/fillgrid',
					data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'datein':datein,'attfilter':attfilter, '_token': token
				},
				type: 'POST',
				dataType:"JSON",
			},
			destroy:true,
			columns: [
			{data:'department', name: 'department'},
			{data:'salary', name: 'salary',width:'8%'},
			{data:'calculatedsalary', name: 'calculatedsalary',width:'8%'},
			{data:'incometax', name: 'incometax',width:'8%'},
			{data:'advance', name: 'advance'},
			{data:'loan', name: 'loan'},
			{data:'allowance1', name: 'allowance1'},					
			{data:'deduction1', name: 'deduction1'},
			{data:'overtimers', name: 'overtimers',width:'6%'},
			{data:'netsalary', name: 'netsalary',width:'7%'},
			{data:'netpayable', name: 'netpayable'},				
			]

		});
	}

	if (attfilter == 'empsalaryhistory'){
			document.getElementById("attMonthlySaltable").style.display = "none";
			document.getElementById("attMonthlySalSumtable").style.display = "none";
			document.getElementById("attEmpSalHistorytable").style.display = "block";
			document.getElementById("attAdvanceDeductionSheettable").style.display = "none";
			document.getElementById("attAdvanceDeductionSummarytable").style.display = "none";
			var groupColumn = 0;
			var table = $('#empSalHistorytable').DataTable({
				columnDefs: [{ "visible": false, "targets": groupColumn }],
       			order: [[ groupColumn, 'asc' ]],
				lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
				processing: true,
				responsive: true,
				serverSide: true,
				paging: false,
				bFilter: false,
				scroller: {
					loadingIndicator: false
				},

				ajax: {
					url:'monthy-salary-report/fillgrid',
					data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'datein':datein,'attfilter':attfilter, '_token': token
				},
				type: 'POST',
				dataType:"JSON",
			},
			destroy:true,
			columns: [
			{data:'employeename', name: 'employeename'},
			{data:'vdate', name: 'vdate'},
			{data:'salary', name: 'salary'},
			{data:'monthdays', name: 'monthdays'},
			{data:'calculatedsalary', name: 'calculatedsalary'},
			{data:'advance', name: 'advance'},
			{data:'loan', name: 'loan'},
			{data:'netpayable', name: 'netpayable'}
			],

			drawCallback: function ( settings ) {
	            var api = this.api();
	            var rows = api.rows( {page:'current'} ).nodes();
	            var last=null;
	 
	            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
	                if ( last !== group ) {
	                    $(rows).eq( i ).before(
	                        '<tr class="dept-group"><td colspan="7">'+group+'</td></tr>'
	                    );
	 
	                    last = group;
	                }
	            } );
	        }
		});
	}

	if (attfilter == 'advancedeductionsheet'){
			document.getElementById("attMonthlySaltable").style.display = "none";
			document.getElementById("attMonthlySalSumtable").style.display = "none";
			document.getElementById("attEmpSalHistorytable").style.display = "none";
			document.getElementById("attAdvanceDeductionSummarytable").style.display = "none";
			document.getElementById("attAdvanceDeductionSheettable").style.display = "block";
			var table = $('#advanceDeductionSheettable').DataTable({
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
					url:'monthy-salary-report/fillgrid',
					data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'datein':datein,'attfilter':attfilter, '_token': token
				},
				type: 'POST',
				dataType:"JSON",
			},
			destroy:true,
			columns: [		
			{data:'empcode', name: 'empcode'},	
			{data:'employeename', name: 'employeename'},
			{data:'department', name: 'department'},
			{data:'designation', name: 'designation'},
			{data:'allowdecutiontype', name: 'allowdecutiontype'},
			{data:'amount', name: 'amount'}		
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
	if (attfilter == 'advancedeductionsummary') {
			document.getElementById("attMonthlySaltable").style.display = "none";
			document.getElementById("attMonthlySalSumtable").style.display = "none";
			document.getElementById("attEmpSalHistorytable").style.display = "none";
			document.getElementById("attAdvanceDeductionSheettable").style.display = "none";
			document.getElementById("attAdvanceDeductionSummarytable").style.display = "block";
			var table = $('#advanceDeductionSummarytable').DataTable({
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
					url:'monthy-salary-report/fillgrid',
					data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'datein':datein,'attfilter':attfilter, '_token': token
				},
				type: 'POST',
				dataType:"JSON",
			},
			destroy:true,
			columns: [		
			{data:'department', name: 'department'},
			{data:'strength', name: 'strength'},
			{data:'allowdecutiontype', name: 'allowdecutiontype'},
			{data:'amount', name: 'amount'}		
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