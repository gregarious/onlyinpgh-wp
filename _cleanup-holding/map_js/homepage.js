//<![CDATA[
  // Global OIP_SITE_URL is set in header.php
  var site_url = OIP_SITE_URL;
  var map;
  var markers = [];
  var places = [];
  var infoWindow;
 	var eventsidebar;
 	var isloggedin;
 	var placeservice;
    //var locationSelect;

//Populates map with events on page load
function populateEventsStart() {
   	 var today = new Date();
     var month = today.getMonth()+1;
     var day = today.getDate();
     var year = today.getFullYear();
     var startdate = + year + "-" + month + "-" + day;
   	
   	//Querty to get events
   	var searchUrl = '/searchevents_initial.php?&startdate=' + startdate;     	
   	//Get and format the XML
       downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);
       var markerNodes = xml.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
    var sidebar = document.getElementById('event_sidebar');
       sidebar.innerHTML = '';
       if (markerNodes.length == 0) {
         sidebar.innerHTML = 'Sorry! None of our event listings matched your search.';
         return;
       }
       //Defines the variables for events with information from the XML
       	document.getElementById('loadmore').value = 'There are no more search results';
    for (var i = 0; i < markerNodes.length; i++) {
         var name = markerNodes[i].getAttribute("ename");
         var eventid = markerNodes[i].getAttribute("eventid");
         var address = markerNodes[i].getAttribute("address");
         var event_date = markerNodes[i].getAttribute("start_date");
         var stime = markerNodes[i].getAttribute("start_time");
         var etime = markerNodes[i].getAttribute("end_time");
         var fans = markerNodes[i].getAttribute("fans");
         var description = markerNodes[i].getAttribute("description");
         var slug = markerNodes[i].getAttribute("slug");
         var pic = markerNodes[i].getAttribute("picture");
         var eventtype = markerNodes[i].getAttribute("type").split(',');
		 var category = eventtype[0];
         var organization = markerNodes[i].getAttribute("organization");
         var distance = parseFloat(markerNodes[i].getAttribute("distance"));
         var latlng = new google.maps.LatLng(
              parseFloat(markerNodes[i].getAttribute("lat")),
              parseFloat(markerNodes[i].getAttribute("lng")));
		//Create a map marker and a sidebar entry for each event
         var marker = createEventMarker(latlng, name, address, organization, category, event_date, stime, etime, pic, slug, eventid, fans, description);
         bounds.extend(latlng);

   		 var eventEntry = createEventSidebar(marker, name, address, distance, fans, organization, category, event_date, stime, etime, pic, slug, latlng, description, eventid);
         sidebar.appendChild(eventEntry);
       }
      });
}

//Perform search with keyword when you press enter
function submitonEnter(evt){
var charCode = (evt.which) ? evt.which : event.keyCode
if(charCode == "13"){
beginSearch();
}
}
//Loads the map on the page
function load() {
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
      //placeservice = new google.maps.places.PlacesService(map);

      eventsidebar = document.getElementById("event_sidebar");

	  populateEventsStart();
}

//Sends a request for Google Places markers/information based on bounds of the map
function placeRequest(ltype) {
	var downtown = map.getCenter();
	
	var ltype;
	if (ltype == 'food'){
	var request = {
    location: downtown,
    radius: '1000',
    types: ['bakery','bar','cafe','food','grocery_or_supermarket']
  };
	} else if (ltype == 'music') {
		var request = {
    location: downtown,
    radius: '1000',
    types: ['night_club']
  };
	} else if (ltype == 'museum') {
		var request = {
    location: downtown,
    radius: '1000',
    types: ['art_gallery','museum']
  };
	} else if (ltype == 'sports') {
		var request = {
    location: downtown,
    radius: '1000',
    types: ['bowling_allery','campground','park','stadium']
  };
	} else if (ltype == 'shops') {
		var request = {
    location: downtown,
    radius: '1000',
    types: ['beauty_salon','bicycle_store','book_store','clothing_store','convenience_store','department_store','electronics_store','florist','furniture_store','hair_care','hardware_store','home_goods_store','jewelry_store','liquor_store','pet_store','pharmacy','shoe_store','shopping_mall','spa','store']
  };
	} else if (ltype == 'theater') {
		var request = {
    location: downtown,
    radius: '1000',
    types: ['movie_theater']
  };
	} else if (ltype == 'attraction'){
		var request = {
    location: downtown,
    radius: '1000',
    types: ['amusement_park','aquarium','casio','library','university']
  };
	}
	
	
	placeservice = new google.maps.places.PlacesService(map);
	var ifchecked = document.getElementById(ltype).checked;
	if (ifchecked == true){
	placeservice.search(request, callback);	
	} else {
		clearPlaces();
	}
				
}

function callback(results, status) {
          if (status == google.maps.places.PlacesServiceStatus.OK) {
              for (var i = 0; i < results.length; i++) {
                  var place = results[i];
                  createMarker(results[i]);
              }
          }
     }

//function requestDetails(place) {
//	var request = {
//		reference: 	place.reference
//	};
//	
//	var service = new google.maps.places.PlacesService(map);
//	service.getDetails(request, callbackdetails);
//}

//function callbackdetails(results, status) {
//	if (status == google.maps.places.PlacesServiceStatus.OK) {
//		for (var i=0; i < results.length; i++) {
//			var place = results[i];
//			createMarker(results[i]);	
//		}
//	}
//}

//Creates map marker for Google places results
function createMarker(place) {
		var ltype = place.types[0];	
//Determines the type of marker to plot based on the place type
if ((ltype == 'bakery')||(ltype == 'bar')||(ltype == 'cafe')||(ltype == 'food')||(ltype == 'grocery_or_supermarket')||(ltype == 'restaurant')){
		var placedot = OIP_SITE_URL + '/place_dots/food.png';
	} 
	else if (ltype == 'night_club') {
var placedot = OIP_SITE_URL + '/place_dots/music.png';
	} 
	else if ((ltype == 'art_gallery')||(ltype == 'museum')) {
var placedot = OIP_SITE_URL + '/place_dots/museum.png';
	} 
	else if ((ltype == 'bowling_allery')||(ltype == 'campground')||(ltype == 'park')||(ltype == 'stadium')) {
		var placedot = OIP_SITE_URL + '/place_dots/spots.png';
	} 
	else if ((ltype == 'beauty_salon')||(ltype == 'bicycle_store')||(ltype == 'book_store')||(ltype == 'clothing_store')||(ltype == 'convenience_store')||(ltype == 'department_store')||(ltype == 'electronics_store')||(ltype == 'florist')||(ltype == 'furniture_store')||(ltype == 'hair_care')||(ltype == 'hardware_store')||(ltype == 'home_goods_store')||(ltype == 'jewelry_store')||(ltype == 'liquor_store')||(ltype == 'pet_store')||(ltype == 'pharmacy')||(ltype == 'shoe_store')||(ltype == 'shopping_mall')||(ltype == 'spa')||(ltype == 'store')) {
		var placedot = OIP_SITE_URL + '/place_dots/shopping.png';
	} 
	else if (ltype == 'movie_theater') {
var placedot = OIP_SITE_URL + '/place_dots/theaterfilm.png';
	} 
	else if ((ltype == 'amusement_park')||(ltype == 'aquarium')||(ltype == 'casio')||(ltype == 'library')||(ltype == 'university')){
var placedot = OIP_SITE_URL + '/place_dots/attractions.png';
	}	
		  var placeLoc = place.geometry.location;
          var marker = new google.maps.Marker({
              map: map,
              icon: placedot,
              position: new google.maps.LatLng(placeLoc.lat(), placeLoc.lng())
          });
          var innerHTML ="<div class=\"infowindow\"><b>" + place.name + "</b><br>" + place.vicinity + "<br><br><a target=\"_blank\" href=\"http://maps.google.com/maps?saddr=&daddr=" + place.geometry.location + "\">Get Directions</a></div>";
          google.maps.event.addListener(marker, 'click', function () {
              infoWindow.setContent(innerHTML);
              infoWindow.open(map, this);
          });
 return places[places.push(marker)-1];
      }
//Clears Google place results from the map      
function clearPlaces() {
     infoWindow.close();
     for (var i = 0; i < places.length; i++) {
       places[i].setMap(null);
     }
     places.length = 0;  
}    
//Clears all markers from the map when you start a new searc
function clearLocations() {
     infoWindow.close();
     for (var i = 0; i < markers.length; i++) {
       markers[i].setMap(null);
     }
     markers.length = 0;  
     
     for (var i = 0; i < places.length; i++) {
       places[i].setMap(null);
     }
     places.length = 0;  
  eventsidebar.innerHTML = "";
}  
//Stars the search
function beginSearch() {
   		searchEvents();
   }
   //Determines which search to perform based on filled in fields
function searchEvents() {
	 var address = document.getElementById("addressInput").value;
	 var search = document.getElementById("searchword").value;
	 document.getElementById('limitvalue').value = new Number(0);
     var geocoder = new google.maps.Geocoder();
     
     if (address!='all' && search == 'Add a keyword (optional)'){
     geocoder.geocode({address: address}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	clearLocations();
       	searchAllEventsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });	
     
     } else if (address!='all' && search == ''){
     geocoder.geocode({address: address}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	clearLocations();
       	searchAllEventsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });	
     
     } else if (address == 'all' && search == 'Add a keyword (optional)'){
     geocoder.geocode({address:'40.44076, -80'}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	clearLocations();
       	searchAllEventsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
          
     } else if (address == 'all' && search == ''){
     geocoder.geocode({address:'40.44076, -80'}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	clearLocations();
       	searchAllEventsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
          
     } else if (address == 'all' && search != 'Add a keyword (optional)'){
          geocoder.geocode({address:'40.44076, -80'}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	clearLocations();
       	searchAllKeyEventsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
     
     } else if (address == 'all' && search != ''){
          geocoder.geocode({address:'40.44076, -80'}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	clearLocations();
       	searchAllKeyEventsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
     
     } else {
     	geocoder.geocode({address: address}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	clearLocations();
       	searchAllKeyEventsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
     }
}
//Loads next group of events into the sidebar and on the map by changing limit in events query
function searchTen() {
	 var limit = document.getElementById('limitvalue').value;
	 //Change this variable based on the number of events you need to load
	 var newlimit = new Number(limit) + new Number(100);
	 document.getElementById('limitvalue').value = newlimit;
	
	 var address = document.getElementById("addressInput").value;
	 var search = document.getElementById("searchword").value;
     var geocoder = new google.maps.Geocoder();
     
	 if (address!='all' && search == 'Add a keyword (optional)'){
     geocoder.geocode({address: address}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	searchAllEventsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });	
     
     } else if (address!='all' && search == ''){
     geocoder.geocode({address: address}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	searchAllEventsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });	
     
     } else if (address == 'all' && search == 'Add a keyword (optional)'){
     geocoder.geocode({address:'40.44076, -80'}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	searchAllEventsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
          
     } else if (address == 'all' && search == ''){
     geocoder.geocode({address:'40.44076, -80'}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	searchAllEventsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
          
     } else if (address == 'all' && search != 'Add a keyword (optional)'){
          geocoder.geocode({address:'40.44076, -80'}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	searchAllKeyEventsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
     
     } else if (address == 'all' && search != ''){
          geocoder.geocode({address:'40.44076, -80'}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	searchAllKeyEventsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
     
     } else {
     	geocoder.geocode({address: address}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	searchAllKeyEventsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
     }
}

//Searches events in a given time span
function searchAllEventsNear(center) {
	var address = document.getElementById('addressInput').value;
	//Determines the search radius
	if (address != 'all'){
		var radius = '7';
	} else {
		var radius = '30';
	}
	//Gets the user input from search fields
     var today = new Date();
     var month = today.getMonth()+1;
     var day = today.getDate();
     var year = today.getFullYear();
     var startdate = + year + "-" + month + "-" + day;
     var sdatepick = document.getElementById('startdate').value;
     var edatepick = document.getElementById('enddate').value;
     var timespan = document.getElementById('spanSelect').value;
     var limitvalue = document.getElementById('limitvalue').value;
     var bydatesearch = document.getElementById('bydate').style.display;
     //Changes query if there user used the date picker to select dates or not
     if (bydatesearch == 'block'){
     var searchUrl = '/searchevents_genxml.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius + '&startdate=' + startdate + '&timespan=' + timespan + '&limitvalue=' + limitvalue;
     } else {
     	var searchUrl = '/searcheventsdatepick_genxml.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius + '&startdate=' + sdatepick + '&enddate=' + edatepick + '&limitvalue=' + limitvalue;     	
     }
     //Gets the XML and changes certain display text to reflect serach results
       downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);
       var markerNodes = xml.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
    var sidebar = document.getElementById('event_sidebar');
       if (markerNodes.length == 0) {
         sidebar.innerHTML = '<div id="noresult"><b>Sorry! None of our event listings matched your search.</b><br><br><b>You could try...</b><br><p>Searching in a different neighborhood<br>Searching in all neighborhoods<br>Searching with a different keyword<br>Removing the keyword from your search<br>Or searching with a different date range</p></div>';
         return;
       }
       if (markerNodes.length < 100) {
       	document.getElementById('loadmore').value = 'There are no more search results';
       } else {
       	document.getElementById('loadmore').value = 'Load Next 100 Events';
       }
       //Defines variables with information from XML
    for (var i = 0; i < markerNodes.length; i++) {
         var name = markerNodes[i].getAttribute("ename");
         var eventid = markerNodes[i].getAttribute("eventid");
         var address = markerNodes[i].getAttribute("address");
         var event_date = markerNodes[i].getAttribute("start_date");
		 var edate = markerNodes[i].getAttribute("end_date");
         var stime = markerNodes[i].getAttribute("start_time");
         var etime = markerNodes[i].getAttribute("end_time");
         var fans = markerNodes[i].getAttribute("fans");
         var description = markerNodes[i].getAttribute("description");
         var slug = markerNodes[i].getAttribute("slug");
         var pic = markerNodes[i].getAttribute("picture");
         var eventtype = markerNodes[i].getAttribute("type").split(',');
		 var category = eventtype[0];
         var organization = markerNodes[i].getAttribute("organization");
         var distance = parseFloat(markerNodes[i].getAttribute("distance"));
         var latlng = new google.maps.LatLng(
              parseFloat(markerNodes[i].getAttribute("lat")),
              parseFloat(markerNodes[i].getAttribute("lng")));
//Creates a map marker and a sidebar entry for each event result
         var marker = createEventMarker(latlng, name, address, organization, category, event_date, stime, etime, pic, slug, eventid, fans, description);
         bounds.extend(latlng);

   		 var eventEntry = createEventSidebar(marker, name, address, distance, fans, organization, category, event_date, stime, etime, pic, slug, latlng, description, eventid, edate);
         sidebar.appendChild(eventEntry);
       }
map.fitBounds(bounds);
      });
}
//Performs an event search with a keyword
function searchAllKeyEventsNear(center) {
var address = document.getElementById('addressInput').value;
	//Determines search radius
	if (address != 'all'){
		var radius = '7';
	} else {
		var radius = '30';
	}
	//Gets user input from search fields
     var today = new Date();
     var month = today.getMonth()+1;
     var day = today.getDate();
     var year = today.getFullYear();
     var startdate = + year + "-" + month + "-" + day;
     var sdatepick = document.getElementById('startdate').value;
     var edatepick = document.getElementById('enddate').value;
     var timespan = document.getElementById('spanSelect').value;
     var searchword = document.getElementById('searchword').value;
     var limitvalue = document.getElementById('limitvalue').value;
     var bydatesearch = document.getElementById('bydate').style.display;
     //Determines which query to use based on if the datepicker was user or not
     if (bydatesearch == 'block'){
     var searchUrl = '/searcheventsall_genxml.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius + '&startdate=' + startdate + '&timespan=' + timespan + '&searchword=' + searchword + '&limitvalue=' + limitvalue;
     } else {
     var searchUrl = '/searcheventsalldatepick_genxml.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius + '&startdate=' + sdatepick + '&enddate=' + edatepick + '&searchword=' + searchword + '&limitvalue=' + limitvalue;    	
     }
     //Gets XML and changes display text to reflect search results
       downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);
       var markerNodes = xml.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
    var sidebar = document.getElementById('event_sidebar');
       if (markerNodes.length == 0) {
         sidebar.innerHTML = '<div id="noresult"><b>Sorry! None of our event listings matched your search.</b><br><br><b>You could try...</b><br><p>Searching in a different neighborhood<br>Searching in all neighborhoods<br>Searching with a different keyword<br>Removing the keyword from your search<br>Or searching with a different date range</p></div>';
         return;
       }
       if (markerNodes.length < 100) {
       	document.getElementById('loadmore').value = 'There are no more search results';
       } else {
       	document.getElementById('loadmore').value = 'Load Next 100 Events';
       }
       //Define variables with information from XML
    for (var i = 0; i < markerNodes.length; i++) {
         var name = markerNodes[i].getAttribute("ename");
         var eventid = markerNodes[i].getAttribute("eventid");
         var address = markerNodes[i].getAttribute("address");
         var event_date = markerNodes[i].getAttribute("start_date");
		 var edate = markerNodes[i].getAttribute("end_date");
         var stime = markerNodes[i].getAttribute("start_time");
         var etime = markerNodes[i].getAttribute("end_time");
         var fans = markerNodes[i].getAttribute("org_fancount");
         var description = markerNodes[i].getAttribute("description");
         var slug = markerNodes[i].getAttribute("slug");
         var pic = markerNodes[i].getAttribute("picture");
         var eventtype = markerNodes[i].getAttribute("type").split(',');
		 var category = eventtype[0];
         var organization = markerNodes[i].getAttribute("organization");
         var distance = parseFloat(markerNodes[i].getAttribute("distance"));
         var latlng = new google.maps.LatLng(
              parseFloat(markerNodes[i].getAttribute("lat")),
              parseFloat(markerNodes[i].getAttribute("lng")));

//Create a map marker and sidebar entry for each event result
         var marker = createEventMarker(latlng, name, address, organization, category, event_date, stime, etime, pic, slug, eventid, fans, description);
         bounds.extend(latlng);

   		 var eventEntry = createEventSidebar(marker, name, address, distance, fans, organization, category, event_date, stime, etime, pic, slug, latlng, description, eventid, edate);
         sidebar.appendChild(eventEntry);
       }
map.fitBounds(bounds);
      });
}
  
//Creates the map marker for the events
function createEventMarker(latlng, name, address, organization, category, event_date, stime, etime, pic, slug, eventid, fans, description) {
	   		isloggedin = document.getElementById("isloggedin").value;
	   		var user = document.getElementById("loggedinid").value;
	//Determines the icon to plot on the map and if it should be considered an attraction or not  		       	
	var icontype;
	if (category == 'Food &amp; Drink' && fans < 100){
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/food.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Theater' && fans < 100){
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/theaterfilm.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Shopping' && fans < 100){
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/retail.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Sports &amp; Outdoors' && fans < 100){
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/outdoors.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Educational' && fans < 100){
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/education.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Arts' && fans < 100){
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/arts.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Music' && fans < 100){
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/music.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	//If event comes in from a feed the event_pic becomes the map marker
	else if (category == 'oip_feeds' && fans < 100){
		var icontype = pic;
	}
	else if (fans < 100) {
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/genfun.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));	
	}
	
	else if (category == 'Food &amp; Drink' && fans > 100){
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/food_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Theater' && fans > 100){
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/theaterfilm_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Music' && fans > 100){
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/music_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Shopping' && fans > 100){
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/retail_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Sports &amp; Outdoors' && fans > 100){
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/outdoors_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Educational' && fans > 100){
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/education_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Arts' && fans > 100){
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/arts_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'oip_feeds' && fans > 100){
		var icontype = pic;
	}
	else {
		var icontype = new google.maps.MarkerImage(OIP_SITE_URL + '/newplace_markers/genfun_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));	
	}

	//If user is logged in changes the function of the Count Me in button
	if (isloggedin == 'y'){
		//If logged in the button will add an entry to attendance table
      var userid = document.getElementById("loggedinid").value;
      var html = "<div class=\"infowindow\"><img src=\"" + pic + "\"><b>" + name + "</b><br/>" + event_date + "<br/>" + stime + " - " + etime + "<br/><br/>" + description + "...<a target=\"_blank\" href=\"" + OIP_SITE_URL + "/events/event/" + slug + "/\">Get more info</a><br/><br/><input type=\"button\" value=\"Count me in!\" id=\"" + eventid + "window\" onclick=\"attendEventWindow(" + eventid + ")\"></div>";
	} else {
		//If they're not logged in the button will promt them to login or register
      	var html = "<div class=\"infowindow\"><img src=\"" + pic + "\"><b>" + name + "</b><br/>" + event_date + "<br/>" + stime + " - " + etime + "<br/><br/>" + description + "...<a target=\"_blank\" href=\"" + OIP_SITE_URL + "/events/event/" + slug + "/\">Get more info</a><br/><br/><input type=\"button\" value=\"Count me in!\" onclick=\"attendEvent(" + eventid + ")\" id=\"" + eventid + "window\"></div>";
      }
	//Used to toggle the event markers
      var customdata = {
		category: category,
		location: 3,
	} 

      var marker = new google.maps.Marker({
        map: map,
        position: latlng,
        icon: icontype,
        data: customdata,
      });
      
            google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
	return markers[markers.push(marker)-1];
}

//Creates sidebar entry for each event
function createEventSidebar(marker, name, address, distance, fans, organization, category, event_date, stime, etime, pic, slug, latlng, description, eventid, edate) {
      var eventdiv = document.createElement('div');
      isloggedin = document.getElementById("isloggedin").value;
      //If user is logged in changes the function of the Count Me in button
      if (isloggedin == 'y'){
      var userid = document.getElementById("loggedinid").value;
      //If logged in the button will add an entry to attendance table
      var html = "<div class=\"eside\"><img src=\"" + pic + "\"> <b>" + name + "</b> <br/><p class=\"hostedby\">Hosted By</p><p class=\"host\">" + organization + "</p>" + address + "<br/><a target=\"_blank\" href=\"http://maps.google.com/maps?saddr=&daddr=" + address + "\">Get Directions</a><br/><br/>" + event_date + "<br/>" + stime + " - " + etime + "<br/><br/>" + description + "... <a target=\"_blank\" href=\"" + OIP_SITE_URL + "/events/event/" + slug + "/\">Get more info</a><br/><br/><input type=\"button\" value=\"Count me in!\" id=\"" + eventid + "\" onclick=\"attendEvent(" + eventid + ")\"></div>";
      } else {
      	//If they're not logged in the button will promt them to login or register
      	var html = "<div class=\"eside\"><img src=\"" + pic + "\"> <b>" + name + "</b> <br/><p class=\"hostedby\">Hosted By</p><p class=\"host\">" + organization + "</p>" + address + "<br/><a target=\"_blank\" href=\"http://maps.google.com/maps?saddr=&daddr=" + address + "\">Get Directions</a><br/><br/>" + event_date + "<br/>" + stime + " - " + etime + "<br/><br/>" + description + "... <a target=\"_blank\" href=\"" + OIP_SITE_URL + "/events/event/" + slug + "/\">Get more info</a><br/><br/><input type=\"button\" value=\"Count me in!\" onclick=\"textLoginChange(" + eventid + ")\" id=\"" + eventid + "\"></div>";
      }
      
      eventdiv.innerHTML = html;
      eventdiv.style.cursor = 'pointer';
      google.maps.event.addDomListener(eventdiv, 'click', function() {
        google.maps.event.trigger(marker, 'click');
      });
      google.maps.event.addDomListener(eventdiv, 'mouseover', function() {
        eventdiv.style.backgroundColor = '#ffec4b';
      });
      google.maps.event.addDomListener(eventdiv, 'mouseout', function() {
        eventdiv.style.backgroundColor = '#fff';
      });
      return eventdiv;
}

//Toggles the event markers on and off
function toggleMarkers(attr,val) {
  if (markers) {
    for (i in markers) {
        if(markers[i].data[attr] == val){
	    var visibility = (markers[i].getVisible() == true) ? false : true;
	    markers[i].setVisible(visibility);
        }
    }
  }
}
//Toggles place markers on and off
//Not using this anymore but may need it in the future
function togglePlaces(attr,val) {
  if (places) {
    for (i in places) {
        if(places[i].data[attr] == val){
	    var visibility = (places[i].getVisible() == true) ? false : true;
	    places[i].setVisible(visibility);
        }
    }
  }
}
//The script for the Count Me in button in the sidebar
function attendEvent(eid) {
	//Uses event_id and the id of the logged in user
   	var user = document.getElementById("loggedinid").value;
   	var eventid = eid;
   	//Gives the information to an insert php script and inserts them in the database
   	var searchUrl = '/insertattend.php?&userid=' + user + '&eventid=' + eventid;       	
       downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);       
       var success = xml.documentElement.getElementsByTagName("success");
       
    for (var i = 0; i < success.length; i++) {
         var message = success[i].getAttribute("message");
       }
      });
      //Changes the button text after the entry is recorded
       document.getElementById(eventid + 'window').value = "You're in!";
       document.getElementById(eventid).value = "You're in!";
       document.getElementById(eventid).onclick = '';
       document.getElementById(eventid + 'window').onclick = '';
}
//Same script as above but for the buttons in the info windows
function attendEventWindow(eid) {
   	var user = document.getElementById("loggedinid").value;
   	var eventid = eid;
   	
   	var searchUrl = '/insertattend.php?&userid=' + user + '&eventid=' + eventid;       	
       downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);       
       var success = xml.documentElement.getElementsByTagName("success");
       
    for (var i = 0; i < successNodes.length; i++) {
         var message = successNodes[i].getAttribute("message");
       }
      });
       document.getElementById(eventid + 'window').value = "You're in!";
       document.getElementById(eventid).value = "You're in!";
       document.getElementById(eventid).onclick = '';
       document.getElementById(eventid + 'window').onclick = '';
}

function textLoginChange(eid) {
	document.getElementById(eventid + 'window').value = "Please login to be counted!";
       document.getElementById(eventid).value = "Please login to be counted!";
       document.getElementById(eventid).onclick = '';
       document.getElementById(eventid + 'window').onclick = '';
		
}


    //Call backs for Google maps marker plotting
    //If any scripts need to be added that depend on the markers existing on the map ADD THEM ABOVE THESE SCRIPTS
function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request.responseText, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function parseXml(str) {
      if (window.ActiveXObject) {
        var doc = new ActiveXObject('Microsoft.XMLDOM');
        doc.loadXML(str);
        return doc;
      } else if (window.DOMParser) {
        return (new DOMParser).parseFromString(str, 'text/xml');
      }
    }

    function doNothing() {}

    //]]>