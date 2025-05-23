(function($) {
    'use strict';
    // permission table
    $(document).ready(function()
    {
        var searchable = [];
        var selectable = []; 
        var token = $('#token').val();

        var dTable = $('#permission_table').DataTable({

            order: [],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            processing: true,
            responsive: true,
            serverSide: true,
            processing: true,
            paging: false,
            dom: "<'row'<'grid-actions col-sm-6'B><'col-sm-6'f>>tipr",
            language: {
              processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>',
              search: "",
              searchPlaceholder: "Search here..."
          },
          scroller: {
            loadingIndicator: false
        },
        bLengthChange : false,
        pagingType: "simple_numbers",
        ajax: {
            url: 'permission/fillgrid',
            type: "get",
            headers: {
              'X-CSRF-TOKEN': token
          }
      },
      columns: [
      {data:'name', name: 'name',"width": "60%" },
      {data:'action', name: 'action', orderable: false, searchable: false}

      ],
      buttons: [
      {
        extend: 'copy',
        className: 'btn-sm btn-info',
        title: 'Roles',
        header: false,
        footer: true,
        exportOptions: {
                        // columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    className: 'btn-sm btn-success',
                    title: 'Roles',
                    header: false,
                    footer: true,
                    exportOptions: {
                        // columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn-sm btn-warning',
                    title: 'Roles',
                    header: false,
                    footer: true,
                    exportOptions: {
                        // columns: ':visible',
                    }
                },
                {
                    extend: 'pdf',
                    className: 'btn-sm btn-primary',
                    title: 'Roles',
                    pageSize: 'A2',
                    header: false,
                    footer: true,
                    exportOptions: {
                        // columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    className: 'btn-sm btn-default',
                    title: 'Roles',
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

            /* 
             * create an element id to change permission names, while inline datatable updated
             */
             createdRow: function ( row, data, index ) {
                var td_index = data.DT_RowIndex;
                $('td', row).eq(0).attr('id', 'perm_'+data.id);
                $('td', row).eq(0).attr('title', 'Click to edit permission');
            },
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


        // datatable inline cell edit
        // only those have manage_permission permission will get access
        // @can is a blade syntax
        dTable.MakeCellsEditable({
            "onUpdate": updatePermission, //call function to update in backend
            "inputCss":'form-control',
            "columns": [0],
            "confirmationButton": { // could also be true
                "confirmCss": 'btn btn-success',
                "cancelCss": 'btn btn-danger'
            },
            "inputTypes": [
            {
                "column": 0,
                "type": "text",
                "options": null
            }

            ]
        });
        //end of permission area
    });
    // datatable inline cell edit callback function
    function updatePermission (updatedCell, updatedRow, oldValue) 
    {
        var id = updatedRow.data().id;
        var name = updatedRow.data().name;
        $.ajax({
            url: "permission/update",
            method: "GET",
            dataType: 'json',
            data: {
                'id' : id,
                'name' : name
            },/*
            headers: {
                'X-CSRF-TOKEN': token
            },*/
            success: function(data)
            {
                $('#perm'+updatedRow.data().id).text(data.name);
                updatedRow.data().name = data.name;
                
            }
        });
    }
    //Delete Button
    $(document).on('click','#deleteRecord' ,function(){
        var id = $('#id').val();    
        window.location.href = 'permission/delete/'+id;
    });

    //Edit Button
    $(document).on('click','#editBtn' ,function(){
        var id=$(this).attr("data");
        $('#id').val(id);
        $.ajax({
            url: 'permission/fillform/'+id,
            type: 'get',

            success:function(data){
                $('#permission').val(data['permission_name']); 
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