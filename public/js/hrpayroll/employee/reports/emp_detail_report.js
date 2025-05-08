(function($) {
	'use strict';
 
	$(document).on('click','#fetch-record' ,function(){
		
		var searchable = [];
		var selectable = []; 
		var etypeid = $('#etypeid').val();
		var locationid = $('#locationid').val();
		var deptid = $('#deptid').val();
		var desgid = $('#desgid').val();
		var genderid = $('#genderid').val();
		var religionid = $('#religionid').val();
		var jobtypeid = $('#jobtypeid').val();
		var ishod = $('#ishod').val();
		var isshiftemployee = $('#isshiftemployee').val();
		var haveot = $('#haveot').val();
		var issalarytobank = $('#issalarytobank').val();
		var leftstatusid = $('#leftstatusid').val();
		var dojfrom = $('#dojfrom').val();
		var dojto = $('#dojto').val();
		var dolfrom = $('#dolfrom').val();
		var dolto = $('#dolto').val();
		var dobfrom = $('#dobfrom').val();
		var dobto = $('#dobto').val();
		var salaryfrom = $('#salaryfrom').val();
		var salaryto = $('#salaryto').val();
		var attfilter     = $("input[type='radio'][name='attfilter']:checked").val();
		if (attfilter == 'empdetailreport'){
		document.getElementById("empDetail").style.display="block";
		document.getElementById("deptWiseStrength").style.display="none";
		document.getElementById("desgWiseStrength").style.display="none";

		var dTableDepartment = $('#empDetailTable').DataTable({
			order: [],
			lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			processing: true,
			responsive: true,
			serverSide: true,
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
				url:'report/fillgrid/',
				data: {'attfilter': attfilter,'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'desgid':desgid,'genderid':genderid
				,'religionid':religionid,'jobtypeid':jobtypeid,'ishod':ishod,'isshiftemployee':isshiftemployee,'haveot':haveot
				,'issalarytobank':issalarytobank,'leftstatusid':leftstatusid,'dojfrom':dojfrom,'dojto':dojto,'dolfrom':dolfrom
				,'dolto':dolto,'dobfrom':dobfrom,'dobto':dobto,'salaryfrom':salaryfrom,'salaryto':salaryto
			},
			type: "get",
		},
		destroy:true,
		columns: [
		{data:'employeename', name: 'employeename', width:'25%'},
		{data:'department', name: 'department', width: '10%'},
		{data:'designation', name: 'designation', width: '10%'},
		{data:'dob', name: 'dob', width: '8%'},
		{data:'doj', name: 'doj', width: '8%'},
		{data:'salary', name: 'salary', width: '8%'},
		{data:'shift', name: 'shift', width: '8%'},
		{data:'timein', name: 'timein', width: '8%'},
		{data:'timeout', name: 'timeout', width: '8%'},
		{data:'cnicno', name: 'cnicno', width: '20%'},
		{data:'company', name: 'company', width: '15%'},					
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
	if (attfilter == 'deptwisestrength'){
		document.getElementById("empDetail").style.display="none";
		document.getElementById("deptWiseStrength").style.display="block";
		document.getElementById("desgWiseStrength").style.display="none";

		var dTableDepartment = $('#deptWiseTable').DataTable({
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
				url:'report/fillgrid/',
				data: {'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'desgid':desgid,'genderid':genderid
				,'religionid':religionid,'jobtypeid':jobtypeid,'ishod':ishod,'isshiftemployee':isshiftemployee,'haveot':haveot
				,'issalarytobank':issalarytobank,'leftstatusid':leftstatusid,'dojfrom':dojfrom,'dojto':dojto,'dolfrom':dolfrom
				,'dolto':dolto,'dobfrom':dobfrom,'dobto':dobto,'salaryfrom':salaryfrom,'salaryto':salaryto,'attfilter':attfilter
			},
			type: "get",
		},
		destroy:true,
		columns: [
		{data:'empcode', name: 'empcode', width : '20%'},
		{data:'employeename', name: 'employeename', width : '45%'},
		{data:'department', name: 'department', width : '40%'},
		{data:'strength', name: 'strength', width : '10%'},				
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
	if (attfilter == 'desgwisestrength'){
		document.getElementById("empDetail").style.display="none";
		document.getElementById("deptWiseStrength").style.display="none";
		document.getElementById("desgWiseStrength").style.display="block";

		var dTableDepartment = $('#desgWiseTable').DataTable({
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
				url:'report/fillgrid/',
				data: {'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'desgid':desgid,'genderid':genderid
				,'religionid':religionid,'jobtypeid':jobtypeid,'ishod':ishod,'isshiftemployee':isshiftemployee,'haveot':haveot
				,'issalarytobank':issalarytobank,'leftstatusid':leftstatusid,'dojfrom':dojfrom,'dojto':dojto,'dolfrom':dolfrom
				,'dolto':dolto,'dobfrom':dobfrom,'dobto':dobto,'salaryfrom':salaryfrom,'salaryto':salaryto,'attfilter':attfilter
			},
			type: "get",
		},
		destroy:true,
		columns: [
		{data:'empcode', name: 'empcode', width : '20%'},
		{data:'employeename', name: 'employeename', width : '45%'},
		{data:'designation', name: 'designation', width : '40%'},
		{data:'strength', name: 'strength', width : '10%'},						
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
	if (attfilter == 'empcardreport'){
		document.getElementById("empDetail").style.display="block";
		document.getElementById("deptWiseStrength").style.display="none";
		document.getElementById("desgWiseStrength").style.display="none";

		var dTableDepartment = $('#empDetailTable').DataTable({
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
				url:'report/fillgrid/',
				data: {'etypeid': etypeid,'locationid':locationid,'deptid':deptid,'desgid':desgid,'genderid':genderid
				,'religionid':religionid,'jobtypeid':jobtypeid,'ishod':ishod,'isshiftemployee':isshiftemployee,'haveot':haveot
				,'issalarytobank':issalarytobank,'leftstatusid':leftstatusid,'dojfrom':dojfrom,'dojto':dojto,'dolfrom':dolfrom
				,'dolto':dolto,'dobfrom':dobfrom,'dobto':dobto,'salaryfrom':salaryfrom,'salaryto':salaryto,'attfilter':attfilter
			},
			type: "get",
		},
		destroy:true,
		columns: [
		{data:'employeename', name: 'employeename'},
		{data:'department', name: 'department'},
		{data:'designation', name: 'designation'},
		{data:'dob', name: 'dob'},
		{data:'doj', name: 'doj'},
		{data:'salary', name: 'salary'},
		{data:'shift', name: 'shift'},
		{data:'timein', name: 'timein'},
		{data:'timeout', name: 'timeout'},
		{data:'cnicno', name: 'cnicno'},
		{data:'company', name: 'company'},					
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