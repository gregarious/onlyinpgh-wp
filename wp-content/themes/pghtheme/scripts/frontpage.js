/* IMPORTANT! Requires map.js to be included beforehand to run */

// Creates map and runs initial homepage load search
function initializeEventResults() {
	var today = new Date();
	var startdate = today.getFullYear() + '-' + (today.getMonth()+1) + '-' + today.getDate();
	clearEventResults( function(){ 
		return performEventSearch( {"startdate":startdate,"limit":40} ); }
		);
}

// as soon as DOM is loaded, initialize the map and start the search
jQuery(document).ready(function() {
	 initializeMap();
	 initializeEventResults();
});