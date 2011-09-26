/* IMPORTANT! Requires map.js to be included beforehand to run */

// Creates map and runs initial homepage load search
function initializeUserEventResults(uid) {
	var today = new Date();
	var startdate = today.getFullYear() + '-' + (today.getMonth()+1) + '-' + today.getDate();
	var uid = parseInt(document.getElementById("loggedinid").value);
	
	clearEventResults( function(){ 
		return performEventSearch( {"startdate":startdate,
									"limit":40,
									"userid":uid,
									"onlyattending":1} ); }
		);
}

// as soon as DOM is loaded, initialize the map and start the search
jQuery(document).ready(function() {
	 initializeMap();	
	 initializeUserEventResults();
});