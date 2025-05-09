(function($) { 
	'use strict';

	// Table Grid
  $(document).ready(function()
  {

    var searchable = [];
    var selectable = []; 

    var dTableDepartment = $('#searchEmployeeTrial_table').DataTable({

      order: [],
      lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      processing: true,
      responsive: false,
      serverSide: true,
      processing: true,
      paging: false,
      language: {
        processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
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
      {data:'empcode', name: 'empcode'},
      {data:'employeename', name: 'employeename'},
      {data:'fathername', name: 'fathername'}, 
      {data:'cnicno', name: 'cnicno'},   
      {data:'department', name: 'department'},
      {data:'doj', name: 'doj'},     

      ],
      buttons: [
      {
        extend: 'copy',
        className: 'btn-sm btn-info',
        title: 'EmployeeInfo',
        header: true,
        footer: true,
        exportOptions: {
                          // columns: ':visible'
                      }
                  },
                  {
                    extend: 'csv',
                    className: 'btn-sm btn-success',
                    title: 'EmployeeInfo',
                    header: true,
                    footer: true,
                    exportOptions: {
                          // columns: ':visible'
                      }
                  },
                  {
                    extend: 'excel',
                    className: 'btn-sm btn-warning',
                    title: 'EmployeeInfo',
                    header: true,
                    footer: true,
                    exportOptions: {
                          // columns: ':visible',
                      }
                  },
                  {
                    extend: 'pdf',
                    className: 'btn-sm btn-primary',
                    title: 'EmployeeInfo',
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
                    title: 'EmployeeInfo',
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

    //End  Table

  });

	//Cancel Button
	$(document).on('click','#cancelBtn', function(){	
    //set Today's date as default
    document.getElementById('doj').valueAsDate = new Date();
    document.getElementById('confirmationdate').valueAsDate = new Date();
	});
	//End Cancel Button

  //Search Edit Button
  $(document).on('click','#searchEditBtn' ,function(){
    var id=$(this).attr("data");
    $.ajax({
      url: 'fillform/'+id,
      type: 'get',
      dataType:"JSON",
        success:function(data){
          window.opener.setValueInParentTrial(data);
          window.close();

          },
          error: function(xhr, status, error) {
            $('.alert').show();
            $('.alert').addClass('error');
            $('.alert_msg').text(xhr.responseText);

          }

      })
  });
  //Search End Edit Button

  //Search Buton
  $(document).on('click','#searchBtnTrial', function(){
    window.open("trial-employee-entry/search", "_blank", "top=200,left=300,width=900,height=600");
  });
  //End Search Button



  // Employee Pic validation
  $(document).on('change','#emppic', function(){
    $('#emppicResult').empty();
    $('#outputemppic').remove();
    var filename=$('#emppic').val();
    if(filename!=''){
      var extdot=filename.lastIndexOf(".")+1;
      var ext=filename.substr(extdot,filename.lenght).toLowerCase();
      if(ext=="jpg" || ext=="png" || ext=="jpeg" )
      {
        var output=document.createElement('img');
        output.id='outputemppic';
        output.src=URL.createObjectURL(event.target.files[0]);
        $('#emppicImgPreview').append(output);
      }
      else
      {
        $('#emppicResult').append('<span class="errorImg">Please select image file</span>');
        $('#emppic').val("");
      }
    }
  });
  //end pic validation

	$('select').select2();
})(jQuery);