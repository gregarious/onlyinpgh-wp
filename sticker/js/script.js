
$(document).ready(function(){
	
	$('#title').hide().delay(200).fadeIn(700);
	$('#email-form').submit(function()
		{
			if( !($.trim($('input:#email-address').val())) ) {
				return false;	
			}
		});	
});


