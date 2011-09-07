function popup(url) 
{
	var width  = 800;
	var height = 600;
	var left   = (screen.width  - width)/2;
	var top    = (screen.height - height)/2;
	var params = 'width='+width+', height='+height;
	params += ', top='+top+', left='+left;
	params += ', directories=no';
	params += ', location=no';
	params += ', menubar=no';
	params += ', resizable=yes';
	params += ', scrollbars=yes';
	params += ', status=no';
	params += ', toolbar=no';
	newwin=window.open(url,'popup', params);
	if (window.focus) {newwin.focus()}
	return false;
}

jQuery(document).ready(function($){
	$(".quick_post_option_icon").live('click', function(){
		var icon = $(this);
		if ($(this).parent().next(".quick_post_option_div").is(':visible')){
			$(this).parent().next(".quick_post_option_div").hide(400, function() {
				icon.css({'background-position' : '-128px -192px'});
			});
		} else {
			$(this).parent().next(".quick_post_option_div").show(400, function() {
				icon.css({'background-position' : '-160px -192px'});
			});
		}
	});
});