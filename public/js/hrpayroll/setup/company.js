(function($) {
	'use strict';
	//Department Data Table
	$(document).ready(function()
	{
		var searchable = [];
		var selectable = []; 

		var dTableDepartment = $('#companyTable').DataTable({

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
				url:'fillgrid',
				type: "get"
			},
			columns: [
			{data:'action', name: 'action', orderable: false, searchable: false},
			{data:'vcode', name: 'vcode'},
			{data:'vname', name: 'vname'},
			{data:'currencyname', name: 'currencyname'},
			{data:'countryname', name: 'countryname'},
			],
			buttons: [
			{
				extend: 'copy',
				className: 'btn-sm btn-info',
				title: 'Company',
				header: true,
				footer: true,
				exportOptions: {
	                        // columns: ':visible'
	                    }
	                },
	                {
	                	extend: 'csv',
	                	className: 'btn-sm btn-success',
	                	title: 'Company',
	                	header: true,
	                	footer: true,
	                	exportOptions: {
	                        // columns: ':visible'
	                    }
	                },
	                {
	                	extend: 'excel',
	                	className: 'btn-sm btn-warning',
	                	title: 'Company',
	                	header: true,
	                	footer: true,
	                	exportOptions: {
	                        // columns: ':visible',
	                    }
	                },
	                {
	                	extend: 'pdf',
	                	className: 'btn-sm btn-primary',
	                	title: 'Company',
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
	                	title: 'Company',
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
		$.ajax({
			url: 'fillform/'+id,
			type: 'get',
			dataType:"JSON",

			success:function(data){

				var parent = $(window.opener.document).contents();

			    parent.find('#outputLogo').remove();
			    parent.find('#outputSign').remove();

				if(data['logoImgsrc']){

					parent.find("#logoImgPreview").append("<img id='outputLogo' src='" +data['logoImgsrc']+ "'>") ;
				}

				if(data['signImgsrc']){

					parent.find("#signImgPreview").append("<img id='outputSign' src='" +data['signImgsrc']+ "'>");
				}

				parent.find("#id").val(data["id"]);
				parent.find("#vcode").val(data["vcode"]);
				parent.find("#vname").val(data["vname"]);
				parent.find("#company_nature").val(data['company_nature']);
				parent.find("#url").val(data["url"]);
				parent.find("#display_url").val(data["display_url"]);
				parent.find("#currencyid").val(data["currencyid"]);
				parent.find("#phone").val(data["phone"]);
				parent.find("#uan").val(data["uan"]);
				parent.find("#fax").val(data["fax"]);
				parent.find("#salestaxno").val(data["salestaxno"]);
				parent.find("#ntn_heading").val(data["ntn_heading"]);
				parent.find("#cnic_heading").val(data["cnic_heading"]);
				parent.find("#registrationno").val(data["registrationno"]);
				parent.find("#timezone").val(data["timezone"]);
				parent.find("#city").val(data["city"]);
				parent.find("#country").val(data["countryid"]).change();
				parent.find("#address").val(data["address"]);
				parent.find("#compdateformat").val(data["compdateformat"]);
				parent.find("#saletaxper").val(data["saletaxper"]);
				parent.find("#vatper").val(data["vatper"]);

				parent.find("#isactive").prop('checked', (data['isactive'] == 1 ? true : false));
				parent.find("#havevat").prop('checked', (data['havevat'] == 1 ? true : false));
				parent.find("#havesaletax").prop('checked', (data['havesaletax'] == 1 ? true : false));

               window.close();


                },
                error: function(xhr, status, error) {

                	$('.alert').show();
                	$('.alert').addClass('error');
                	$('.alert_msg').text(xhr.responseText);

                }

            })
		
	});
	//End Edit Button
	//Search Buton
	$(document).on('click','#search-button', function(){
		window.open("company/search", "_blank", "top=200,left=300,width=900,height=600");
	});
	//Search Button
	$(document).on('change','#logo', function(){
		$('#logoResult').empty();
		$('#outputLogo').remove();
		var filename=$('#logo').val();
		if(filename!=''){
			var extdot=filename.lastIndexOf(".")+1;
			var ext=filename.substr(extdot,filename.lenght).toLowerCase();
			if(ext=="jpg" || ext=="png" || ext=="jpeg" )
			{
				var output=document.createElement('img');
				output.id='outputLogo';
				output.src=URL.createObjectURL(event.target.files[0]);
				$('#logoImgPreview').append(output);
			}
			else
			{
				$('#logoResult').append('<span class="errorImg">Please select image file</span>');
				$('#logo').val("");
			}
		}
	});

	$(document).on('change','#signature_image', function(){
	    $('#signResult').empty();
	    $('#outputSign').remove();
		var filename=$('#signature_image').val();
		if(filename!=''){
			var extdot=filename.lastIndexOf(".")+1;
			var ext=filename.substr(extdot,filename.lenght).toLowerCase();
			if(ext=="jpg" || ext=="png" || ext=="jpeg" )
			{
				var output=document.createElement('img');
				output.id='outputSign';
				output.src=URL.createObjectURL(event.target.files[0]);
				$('#signImgPreview').append(output);
			}
			else
			{
				$('#signResult').append('<span class="errorImg">Please select image file</span>');
				$('#signature_image').val("");


			}
		}
	});
	

	//$('select').select2();
})(jQuery);