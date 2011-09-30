
var map;
var infoWindow;
var infoWindowFocus;	// will either be a marker type or 'event'

var visible_place_markers = [];		// maps { place-type : array of place icons }
var event_instances = [];	// global array of event instance objects returned by searches
var cached_search_opts = null;


/** various lookup tables **/
// specifies lat/long value of each option in the events seach dropdown box
region_coordinate_map = { 'all' : 	[40.440318,-79.999218],
						  'city' : 	[40.440318,-79.999218],
						  'west' : 	[40.57522,-80.004],
						  'north' : [40.35582,-79.995],
						  'south' : [40.44054,-79.819],
						  'east' : 	[40.44315,-80.135] };

// maps oips place type labels (e.g. 'food') to Google place type labels (e.g. 'food' -> ['bakery','bar','cafe',...])
oip_google_placetype_map = { 'food' : 	['bakery','bar','cafe','food','grocery_or_supermarket'],
							 'music' : 	['night_club'],
							 'museum' : ['art_gallery','museum'],
							 'sports' : ['bowling_allery','campground','park','stadium'],
							 'shops' : 	['beauty_salon','bicycle_store','book_store','clothing_store','convenience_store','department_store','electronics_store','florist','furniture_store','hair_care','hardware_store','home_goods_store','jewelry_store','liquor_store','pet_store','pharmacy','shoe_store','shopping_mall','spa','store'],
							 'theater' : ['movie_theater'],
							 'attractions' : ['amusement_park','aquarium','casino','library','university'] };

// maps a place type to an image filename (excluding the extension) in the images/place_dots folder 
placemarker_imagebase_map = { 'food' :'food', 
							 'music' : 'music',
							 'museum' : 'museum',
							 'sports' : 'sports',
							 'shops' : 'shopping',
							 'theater' : 'theaterfilm',
							 'attractions' : 'attractions' };
function getPlaceMarkerIconURL(marker_type) {
	var img_dir = '/wp-content/themes/pghtheme/images/place_markers';
	var base = placemarker_imagebase_map[marker_type]; 
	return img_dir + '/' + base + '.png';	
}

// maps an event category to an image filename (excluding the extension) in the images/place_dots folder 
eventcat_imagebase_map = {	'Food &amp; Drink' : 'food',
						  	'Theater' : 'theaterfilm',
						  	'Shopping' : 'retail',
						  	'Sports &amp; Outdoors' : 'outdoors',
						  	'Educational' : 'education',
						  	'Arts' : 'arts',
						  	'Music' : 'music' };
function getEventMarkerIconURL(marker_cat,is_popular) {
	var img_dir = '/wp-content/themes/pghtheme/images/event_markers';
	var base = eventcat_imagebase_map[marker_cat]; 
	if(base === undefined) {
		base = 'genfun';
	}
	return img_dir + '/' + base + (is_popular ? '_attraction' : '') + '.png';
}


// converts a bare JSON object to an EventInstance (note: method changes input object!)
function JSONToEventInstance(json) {
	inst = json;

	inst.main_category = null;
	// if at least one categories exists, choose the first to be the main one
	if(inst.categories && inst.categories.length) {
		inst.main_category = inst.categories[0];
	} 

	inst.marker = null;
	// if we have a lat and long, create a maps marker
	if( inst.location.lat && inst.location.long ) {
		var iconURL = getEventMarkerIconURL(inst.main_category,inst.organization.fancount>=100);
		var icon =  new google.maps.MarkerImage( iconURL,
			new google.maps.Size(20, 20),
			new google.maps.Point(0,0),
			new google.maps.Point(0, 20));
		var latlng = new google.maps.LatLng(inst.location.lat,inst.location.long);
		inst.marker = new google.maps.Marker( { icon: icon,
											position: latlng } );
	}

	inst.toInfoWindowHTML = function() {
			var html = '<div class="infowindow">';
			html += '<h4 class="event-name">' + this.name + '</h4>';
			html += '<img src="' + this.image_url + '">';
			html += '<div class="alignright" id="host-address">';
			html += '<p class="hostedby">Hosted By</p><h4 class="host">' + this.organization.name + '</h4>';
			html += '<p class="event-address">' + this.location.address + '<br>';
			html += '</div>'; // #host-address
			html += '<div id="time-directions">';
			html +=	'<p class="event-time">' + this.timespan.start_date + '<br>';
			html += this.timespan.start_time + ' - ' + this.timespan.end_time;
			html += '<a class="directions alignright" target="_blank" href="http://maps.google.com/maps?saddr=&daddr=' + this.location.address + '">Get Directions &rarr;</a></p>';
			html += '</div>'; // #time-directions
			html += '<p class="event-desc">' + this.description + '</p>...<a target="_blank" href="/events/event/' + this.wp_slug + '/">More info</a>';
			if(this.attending) {
				html += '<input class="attend-button-in alignright" type="button" value="You\'re In!" class="attend-button" id="' + this.id + 'window">';
			}
			else {
				html += '<input class="attend-button alignright" type="button" value="Count me in!" class="attend-button alignright" id="' + this.id + 'window" onclick="attendEvent(' + this.id + ')">';	
			}
			html += '</div>'; // #infowindow

			return html;
		}

	inst.toSidebarEntryHTML = function() {
			var html = '<h4 class="event-name">' + this.name + '</h4>';
			html += '<img src="' + this.image_url + '">';
			html += '<div class="alignright" id="host-address">';
			html += '<p class="hostedby">Hosted By</p><h4 class="host">' + this.organization.name + '</h4>';
			html += '<p class="event-address">' + this.location.address + '<br>';
			html += '</div>'; // #host-address
			html += '<div id="time-directions">';
			html +=	'<p class="event-time">' + this.timespan.start_date + '<br>';
			html += this.timespan.start_time + ' - ' + this.timespan.end_time;
			html += '<a class="directions alignright" target="_blank" href="http://maps.google.com/maps?saddr=&daddr=' + this.location.address + '">Get Directions &rarr;</a></p>';
			html += '</div>'; // #time-directions
			html += '<p class="event-desc">' + this.description + '</p>...<a target="_blank" href="/events/event/' + this.wp_slug + '/">More info</a>';
			if(this.attending) {
				html += '<input class="attend-button-in alignright" type="button" value="Added" class="attend-button" id="' + this.id + 'window">';
			}
			else {
				html += '<input class="attend-button alignright" type="button" value="Add to MyPgh" class="attend-button alignright" id="' + this.id + 'window" onclick="attendEvent(' + this.id + ')">';	
			}
			
			return html;
		}

	return inst;
}

// hacky little thing put together to make sure the Count me in buttons are always in sync.
// There are MUCH better solutions, but it would be obsolete in a month.
function updateEventAttendance(eid) {
	for(var i = 0; i < event_instances.length; i++) {
		if(eid == event_instances[i].id) {
			event_instances[i].attending = 1;
			event_instances[i] = JSONToEventInstance(event_instances[i]);
			eventClicked(event_instances[i]);
			document.getElementById(eid + 'window').value = "You're in!";
       		document.getElementById(eid + 'window').onclick = '';
			break;
		}
	}
}

// makes AJAX call to server and updates display with new event instances
// search opts can be any of the following: 'lat', 'long', 'rad', 'startdate',
//		'enddate', 'search_terms', 'limit', or 'offset'
// Return type of the PHP call is an array of JSON objects with the 
// 		following "prototype":
// 		{
//			'more_results' : boolean
//			'events' : {
// 				id 			: number,
// 	 			name 		: string,
// 				wp_slug  	: string,
// 				description : string,
// 				categories 	: [string],
// 				image_url 	: string,
// 				timespan 	: {
// 					start_date  : string,
// 					start_time  : string,
// 					end_date	: string,		
// 					end_time	: string }
// 				location 	: {
// 					address  	: string,
// 					lat 	 	: number,
// 					long 	 	: number }
// 				organization : {
// 					name 	 	: string,
// 					url 	 	: string,
// 					fancount 	: number }
//			}
// 		}
function performEventSearch(search_opts) {
	var search_status = jQuery('#sidebar-search-status');
	search_status.fadeIn('fast');
	var isloggedin = document.getElementById("isloggedin").value;
	if(isloggedin=='y') {
		search_opts['userid'] = parseInt(document.getElementById("loggedinid").value);
	}

	cached_search_opts = jQuery.extend({},search_opts);	// save these for findMoreResults calls
	var callback = function(json_result) {
			var event_insts = []
			search_status.hide();
			var json_events = json_result['events'];
			for(var i = 0; i < json_events.length; i++) {
				event_insts.push(JSONToEventInstance(json_events[i]));
			}
			updateEventResults(event_insts,json_result['more_results']);
		}
	jQuery.getJSON('/searchevents.php',search_opts,callback);
}

function eventClicked(event_instance) {
	if(event_instance.marker) {
		infoWindow.setContent(event_instance.toInfoWindowHTML());
	    infoWindow.open(map, event_instance.marker);
	    infoWindowFocus = 'event';
	}
	else {
		infoWindow.close();
		infoWindowFocus = null;
	}
}

function updateEventResults(new_events,more_results) {
	var bounds = new google.maps.LatLngBounds();
	var sidebar = jQuery('#sidebar-content');

	var index0 = event_instances.length;
	for( var i = 0; i < new_events.length; i++ ) {
		var event_inst = new_events[i];
		// For now we're just not displaying events without markers
		if(!event_inst.marker) {
			continue;
		}
		var new_ediv = jQuery('<div class="event-listing" id="listing-' + (index0+i) + '">');
		var bound_click_callback = (function(inst) {
					return function() { eventClicked(inst); }
				})(event_inst);
		// click callback function with this event instance bound to it
		new_ediv.click(bound_click_callback);
		
		// if the event has a marker associated with it, display it and add click events
		if(event_inst.marker) {
			new_ediv.addClass('has-marker');
			// add the click listener to the marker (for info window) and add it to the map 
			event_inst.marker.setMap(map);
			google.maps.event.addListener(event_inst.marker, 'click', bound_click_callback);
			bounds.extend(event_inst.marker.getPosition());
		}
		else {
			new_ediv.addClass('no-marker');
		}
		new_ediv.html(event_inst.toSidebarEntryHTML());
		sidebar.append(new_ediv);
	
		event_instances.push(event_inst);
		event_inst = null;
	}

	var footer = jQuery('#sidebar-footer');
	footer.html('');	
	sidebar.slideDown('slow',function()
		{
			var loadmore = '<input id="loadmore" type="button" onclick="findMoreResults(20)" value="Load More Events">';
			if(more_results) {
				footer.html(loadmore);
				footer.show();
			}
		});
}

// grabs event search options from form
function extractSearchOptions() {
	var region = document.getElementById('regionSelector').value;
	var search = document.getElementById('keywordsearch').value;
	var timespan_days = parseInt(document.getElementById('timespanSelect').value);
	var limitval = 30;

	var bydatesearch = document.getElementById('bydate').style.display == 'block';
	var startdate, enddate;
	if(bydatesearch) {
		var today = new Date();
		var endday = new Date();
		endday.setDate(today.getDate()+timespan_days);
		startdate = today.getFullYear() + "-" + (today.getMonth()+1) + "-" + today.getDate();
		enddate = endday.getFullYear() + "-" + (endday.getMonth()+1) + "-" + endday.getDate();	
	}
	else {
		startdate = document.getElementById('startdate').value;
     	enddate = document.getElementById('enddate').value;
	}
	
	var latlng = region_coordinate_map[region];
	var search_opts = { "lat" : latlng[0], 
						"long" : latlng[1],
						"startdate" : startdate,
						"enddate" : enddate,
						"rad" : (region=='all') ? 7 : 30,	// TODO: revisit this radius hack
						"limit" : limitval };
	if( search !== 'Keyword search (optional)' && search != '' ) {
		search_opts['search_terms'] = search;
	}
	return search_opts;
}

// Clears all event markers from map and collapses sidebar, replacing its contents with an optional string
function clearEventResults(callback) {
	if(infoWindowFocus == 'event') {
		infoWindow.close();
		infoWindowFocus = null;
	}

	// Slide results window up
	content = jQuery('#sidebar-content');
	content.slideUp('fast', function() {
		content.html('');
		return callback(); } );

	jQuery('#sidebar-footer').html('');

	// clear all event markers from map
	for(var i = 0; i < event_instances.length; i++) {
		if(event_instances[i].marker) event_instances[i].marker.setMap(null);
	}
	event_instances.length = 0;
}

// Clears all Google place markers from the map of a specific type
function clearPlaceMarkers(ltype) {
	marker_list = visible_place_markers[ltype];
	for (var i = 0; i < marker_list.length; i++) {
		marker_list[i].setMap(null);
	}
	marker_list.length = 0;
	if(infoWindowFocus == ltype) {
		infoWindow.close();
		infoWindowFocus = null;
	}
}

// Clears all Google place markers from the map of a all types
function clearAllPlaceMarkers() {
	for( var ltype in oip_google_placetype_map ) {
		clearPlaceMarkers(ltype);
	}
}

function placeRequestClicked(ltype) {
	// if checked, get place markers from Google Places within
	if (document.getElementById(ltype).checked) {
		var request = {
			location: map.getCenter(),
			radius: '1000',			// GDN: valuable hardcode?
			types: oip_google_placetype_map[ltype]
		}
		var iconURL =  getPlaceMarkerIconURL(ltype);
		var placeservice = new google.maps.places.PlacesService(map);
		placeservice.search( request, 
			function(results,status) {
				if (status == google.maps.places.PlacesServiceStatus.OK) {
					// ensure an array is in place to hold this marker type
					if (!(ltype in visible_place_markers)) {
						visible_place_markers[ltype] = [];
					}
					// for each place, create a marker and add it to the visible list
					for (var i = 0; i < results.length; i++) {
						var place = results[i];
						var marker = createPlaceMarker(results[i],ltype)
						visible_place_markers[ltype].push(marker);
					}
				}			
			} );
	}
	else {
		clearPlaceMarkers(ltype);
	}
}

// Returns a new map marker with the given properties set (this function 
// displays the marker on the global map automatically)
function createPlaceMarker(place,ltype) {
	var placeLoc = place.geometry.location;
	var iconURL = getPlaceMarkerIconURL(ltype);
	var marker = new google.maps.Marker(
		{	map: 		map,
			icon: 		iconURL,
			position: 	new google.maps.LatLng(placeLoc.lat(), placeLoc.lng())
		});
	var innerHTML = '<div class="infowindow">' + 
						'<b>' + place.name + '</b><br/>' + 
						place.vicinity + '<br><br>' + 
						'<a target="_blank" href="http://maps.google.com/maps?saddr=&daddr=' + place.geometry.location + '>' +
							'Get Directions' +
						'</a></div>';

	// add a listener to display an info window on click
	google.maps.event.addListener(marker, 'click', 
		function () {
			infoWindow.setContent(innerHTML);
			infoWindow.open(map, this);
			infoWindowFocus = ltype;
		});
	return marker;
}

// User wants to run a new search using form parameters
function newSearchRequested() {
	var opts = extractSearchOptions();
	clearEventResults( function(){ 
		return performEventSearch( opts ); }
		);
//	performEventSearch( opts );	
}

// Return more results from the last run search
function findMoreResults(num_results) {
	if(!cached_search_opts) { return; }	// should never happen, but silently fail if it does
	jQuery('#sidebar-footer').fadeOut('fast');
	var search_opts = jQuery.extend({},cached_search_opts);
	var orig_offset = cached_search_opts['offset'] ? cached_search_opts['offset'] : 0;
	search_opts['offset'] = orig_offset + cached_search_opts['limit'];
	search_opts['limit'] = num_results;
	performEventSearch(search_opts);
}

//Perform search with keyword when you press enter
function submitonEnter(evt){
	var charCode = (evt.which) ? evt.which : event.keyCode;
	if(charCode == "13") {
		newSearchRequested();
	}
}

//The script for the Count Me in button in the sidebar
function attendEvent(eid) {
	var isloggedin = document.getElementById("isloggedin").value;
	if(!isloggedin=='y') {
		textLoginChange(eid);
		return;
	}
	//Uses event_id and the id of the logged in user
   	var user = document.getElementById("loggedinid").value;
   	var eventid = eid;
   	jQuery.getJSON('/insertattend.php',
   					{ 'eventid':eventid,
   					  'userid':user },
   					  function(json_data) {
   					  		if(json_data['status'] == 'success') {
   					  			updateEventAttendance(eventid);							
   					  		}
						});
}

function textLoginChange(eid) {
	document.getElementById(eventid + 'window').value = "Please login to be counted!";
       document.getElementById(eventid).value = "Please login to be counted!";
       document.getElementById(eventid).onclick = '';
       document.getElementById(eventid + 'window').onclick = '';
		
}

function initializeMap() {
	map = new google.maps.Map(document.getElementById("map"), {
		center: new google.maps.LatLng(40.44076, -80),
		zoom: 12,
		maxZoom: 18,
		minZoom: 11,
		mapTypeId: 'roadmap',
		scrollwheel: false,
		mapTypeControl: false,
		zoomControl: true,
		zoomControlOptions: {style: google.maps.ZoomControlStyle.LARGE},
	});
	infoWindow = new google.maps.InfoWindow();
	infoWindowFocus = null;
}

