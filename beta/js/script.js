
$(document).ready(function(){
	
	$('.points').hide().delay(200).fadeIn(800);
	$('#email-form').submit(function()
		{
			if( !($.trim($('input:#email-address').val())) ) {
				return false;	
			}
		});	
});


