/* IMPORTANT! Requires map.js to be included beforehand to run */

// Creates map and runs initial homepage load search
function initializeEventResults() {
	var today = new Date();
	var startdate = today.getUTCFullYear() + '-' + 
                    (today.getUTCMonth()+1) + '-' + 
                    today.getUTCDate() + ' ' +
                    today.getUTCHours() + ':' +
                    today.getUTCMinutes();
	clearEventResults( function(){ 
		return performEventSearch( {"startdate":startdate,"limit":40} ); }
		);
}

function displayNoResultsMessage(jquery_obj) {
	jquery_obj.html( '<div id="noresult">' +
					'<b>Sorry! None of our event listings matched your search.</b><br/>' +
					'<br/>' +
					'<b>You could try...</b><br/>' +
					'<p>Searching in a different neighborhood<br/>' +
					'Searching in all neighborhoods<br/>' +
					'Searching with a different keyword<br/>' +
					'Removing the keyword from your search<br/>' +
					'Or searching with a different date range</p>' +
				   '</div>' );
}

// as soon as DOM is loaded, initialize the map and start the search
jQuery(document).ready(function() {
	
	jQuery('#top').click(function(){
		jQuery('body').animate({scrollTop:0},1000);
	});
	
    jQuery('ul.eventbuttons').children().click(function(){
        jQuery('ul.eventbuttons').children().removeClass('eventbuttonsyellow');
        jQuery(this).addClass("eventbuttonsyellow");
    });

	initializeMap();
	initializeEventResults();

});

