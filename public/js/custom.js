$(document).on('click','#deleteBtn' ,function(){

	var id=$(this).attr("data");
	$('#id').val(id);   
});

//Vcode field validation
$('#vcode').bind('input', function() {

	var c = this.selectionStart,
	r = /[^a-z0-9 ]/gi,
	v = $(this).val();

	if(r.test(v)) {

		$(this).val(v.replace(r, ''));
		c--;
	}

	this.setSelectionRange(c, c);
});

//Cancel Button
$(document).on('click','#cancelBtn', function(){
	
	/*$('form').trigger('reset');
	$('select').select2({
    	allowClear: true 
    });*/

    location.reload();
});
//End Cancel Button

$(".alert").fadeTo(2000, 800).slideUp(800, function(){
	$(".alert").slideUp(800);
});

function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode != 58 && charCode > 31 
		&& (charCode < 48 || charCode > 57))
		return false;

	return true;
}