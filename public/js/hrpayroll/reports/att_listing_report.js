(function($) {
	'use strict';
	//set Today's date as default
	document.getElementById('vdate').valueAsDate = new Date();

	// $('#unpostedAttSummary input:radio').click(function() {
	// 	$('#employeeid').prop('disabled', true);
	// });

	$(document).on('click','#fillBtn' ,function(){
		
		var searchable = [];
		var selectable = []; 
		var employeeid = $('#employeeid').val();
		var etypeid = $('#etypeid').val();
		var locationid = $('#locationid').val();
		var deptid = $('#deptid').val();
		var vdate = $('#vdate').val();
		var attfilter     = $("input[type='radio'][name='attfilter']:checked").val();
		if (attfilter == 'postedAtt'){
			document.getElementById("attlistingpostedtable").style.display="block";
			document.getElementById("attlistingupostedtable").style.display="none";
			document.getElementById("attunpostedAttSummarytable").style.display = "none";
			document.getElementById("attpostedAttSummarytable").style.display = "none";
			document.getElementById("attAbsenteeListTable").style.display = "none";

			var upostedreporttable = $('#postedreporttable').DataTable({
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
					url:'attendance-listing-report/fillgrid/',
					data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'vdate':vdate,'attfilter':attfilter
				},
				type: "get",
			},
			destroy:true,
			columns: [
			{data:'empcode', name: 'empcode'},
			{data:'employeename', name: 'employeename'},
			{data:'department', name: 'department'},
			{data:'designation', name: 'designation'},
			{data:'starttime', name: 'starttime'},
			{data:'datein', name: 'datein'},
			{data:'dateout', name: 'dateout'},	
			{data:'tottime', name: 'tottime'},
			{data:'attname', name: 'attname'},					
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

	if (attfilter == 'unpostedAtt'){
			document.getElementById("attlistingpostedtable").style.display="none";
			document.getElementById("attlistingupostedtable").style.display="block";
			document.getElementById("attunpostedAttSummarytable").style.display = "none";
			document.getElementById("attpostedAttSummarytable").style.display = "none";
			document.getElementById("attAbsenteeListTable").style.display = "none";

			var upostedreporttable = $('#upostedreporttable').DataTable({
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
					url:'attendance-listing-report/fillgrid/',
					data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'vdate':vdate,'attfilter':attfilter
				},
				type: "get",
			},
			destroy:true,
			columns: [
			{data:'empcode', name: 'empcode'},
			{data:'employeename', name: 'employeename'},
			{data:'department', name: 'department'},
			{data:'designation', name: 'designation'},
			{data:'datein', name: 'datein'},
			{data:'dateout', name: 'dateout'},	
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

		if (attfilter == 'unpostedAttSummary'){
			document.getElementById("attlistingpostedtable").style.display="none";
			document.getElementById("attlistingupostedtable").style.display="none";
			document.getElementById("attunpostedAttSummarytable").style.display="block";
			document.getElementById("attpostedAttSummarytable").style.display = "none";
			document.getElementById("attAbsenteeListTable").style.display = "none";

			var unpostedAttSummarytable = $('#unpostedAttSummarytable').DataTable({
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
					url:'attendance-listing-report/fillgrid/',
					data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'vdate':vdate,'attfilter':attfilter
				},
				type: "get",
			},
			destroy:true,
			columns: [
			{data:'Department', name:'Department'},
			{data:'Strength', name:'Strength'},
			{data:'Absentee', name:'Absentee'},
			{data:'Present', name:'Present'},
			{data:'PPer', name:'PPer'}				
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

		if (attfilter == 'postedAttSummary'){
			document.getElementById("attlistingpostedtable").style.display = "none";
			document.getElementById("attlistingupostedtable").style.display = "none";
			document.getElementById("attunpostedAttSummarytable").style.display = "none";
			document.getElementById("attpostedAttSummarytable").style.display = "block";
			document.getElementById("attAbsenteeListTable").style.display = "none";

			var unpostedAttSummarytable = $('#postedAttSummarytable').DataTable({
				order: [],
				lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
				processing: true,
				responsive: true,
				serverSide: true,
				processing: true,
				paging: true,
				pageLength: 200,
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
					url:'attendance-listing-report/fillgrid/',
					data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'vdate':vdate,'attfilter':attfilter
				},
				type: "get",
			},
			destroy:true,
			columns: [
			{data:'department', name:'department'},
			{data:'strength', name:'strength'},
			{data:'absentee', name:'absentee'},
			{data:'present', name:'present'},
			{data:'pper', name:'pper'}				
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

		if (attfilter == 'absenteeList'){
			document.getElementById("attlistingpostedtable").style.display = "none";
			document.getElementById("attlistingupostedtable").style.display = "none";
			document.getElementById("attunpostedAttSummarytable").style.display = "none";
			document.getElementById("attpostedAttSummarytable").style.display = "none";
			document.getElementById("attAbsenteeListTable").style.display = "block";
			var unpostedAttSummarytable = $('#absenteeListTable').DataTable({
				order: [],
				lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
				processing: true,
				responsive: true,
				serverSide: true,
				processing: true,
				paging: true,
				pageLength: 200,
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
					url:'attendance-listing-report/fillgrid/',
					data: {'employeeid':employeeid,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'vdate':vdate,'attfilter':attfilter
				},
				type: "get",
			},
			destroy:true,
			columns: [
			{data:'empcode', name: 'empcode'},
			{data:'employeename', name:'employeename'},
			{data:'department', name:'department'},
			{data:'designation', name:'designation'},
			{data:'Remarks', name:'Remarks'}				
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