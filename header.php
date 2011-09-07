<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />	
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	<title><?php
	if(is_home()) {
		echo bloginfo('name').' - Home';
	} elseif(is_category()) {
		echo 'Browsing the Category ';
		wp_title(' ', true, '');
	} elseif(is_archive()){
		echo 'Browsing Archives of';
		wp_title(' ', true, '');
	} elseif(is_search()) {
		echo 'Search Results for "'.$s.'"';
	} elseif(is_404()) {
		echo '404 - Page got lost!';
	} else {
		bloginfo(ënameí); wp_title('-', true, '');
	}
	?></title>

<?php do_action( 'bp_head' ) ?>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<link type="text/css" href="http://jqueryui.com/latest/themes/base/ui.all.css" rel="stylesheet" />
<?php wp_head(); ?>

<link href='http://onlyinpgh.com/test/wp-includes/js/jquery/jquery.js' type="text/javascript" />
<link href='http://onlyinpgh.com/test/wp-includes/js/jquery/ui.tabs.js' type="text/javascript" />
<link href='http://onlyinpgh.com/test/wp-includes/js/jquery/ui.widget.js' type="text/javascript" />
<link href='http://onlyinpgh.com/test/wp-includes/js/jquery/ui.datepicker.js' type="text/javascript" />
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script type="text/javascript">

//<![CDATA[
    var map;
    var markers = [];
    var places = [];
    var infoWindow;
 	var eventsidebar;
 	var isloggedin;
    //var locationSelect;

function populateEventsStart() {
   	 var today = new Date();
     var month = today.getMonth()+1;
     var day = today.getDate();
     var year = today.getFullYear();
     var startdate = + year + "-" + month + "-" + day;
   	
   	var searchUrl = '/test/searchevents_initial.php?&startdate=' + startdate;     	
   	
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
       	document.getElementById('loadmore').value = 'There are no more search results';
    for (var i = 0; i < markerNodes.length; i++) {
         var name = markerNodes[i].getAttribute("ename");
         var eventid = markerNodes[i].getAttribute("eventid");
         var address = markerNodes[i].getAttribute("address");
         var sdate = markerNodes[i].getAttribute("start_date");
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
         
         var m_names = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
         var d = new Date(sdate);
         var s_day = d.getDate() + new Number(1);
         var s_month = d.getMonth();
         var s_year = d.getFullYear();
		 var event_date = m_names[s_month] + " " + s_day + ", " + s_year;


         var marker = createEventMarker(latlng, name, address, fans, organization, category, event_date, stime, etime, pic, slug, eventid);
         bounds.extend(latlng);

   		 var eventEntry = createEventSidebar(marker, name, address, distance, fans, organization, category, event_date, stime, etime, pic, slug, latlng, description, eventid);
         sidebar.appendChild(eventEntry);
       }
      });
}

function populateLocationsStart() {	
   	
    var searchUrl = '/test/searchlocations_initial.php';
     
     downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);
       var locationNodes = xml.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
         for (var i = 0; i < locationNodes.length; i++) {
         var fans = locationNodes[i].getAttribute("fans");
         var slug = locationNodes[i].getAttribute("slug");
         var pic = locationNodes[i].getAttribute("picture");
         var category = locationNodes[i].getAttribute("type");
         var organization = locationNodes[i].getAttribute("organization");
         var address = locationNodes[i].getAttribute("address");
         var latlng = new google.maps.LatLng(
              parseFloat(locationNodes[i].getAttribute("lat")),
              parseFloat(locationNodes[i].getAttribute("lng")));

         var place = createLocationMarker (latlng, fans, organization, category, pic, address, fans);
    	bounds.extend(latlng);
    	
       }
      });
}

function submitonEnter(evt){
var charCode = (evt.which) ? evt.which : event.keyCode
if(charCode == "13"){
beginSearch();
}
}

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

      eventsidebar = document.getElementById("event_sidebar");

	  populateEventsStart();
	  searchLocations();
}

function hidePlaces() {
  if (places) {
    for (i in places) {
      places[i].setMap(null);
    }
  }
}

   
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

function beginSearch() {
   		searchEvents();
   		searchLocations();
   }
   
   
function searchLocations() {
	 var address = document.getElementById("addressInput").value;
     var search = document.getElementById("searchword").value;
     var geocoder = new google.maps.Geocoder();
     
     if (address!='all'){
     geocoder.geocode({address: address}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	searchAllLocationsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
     
     } else {
     geocoder.geocode({address:'40.44076, -80'}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
       	searchAllLocationsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
          
     }   
     }
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

function searchTen() {
	 var limit = document.getElementById('limitvalue').value;
	 var newlimit = new Number(limit) + new Number(10);
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


function searchAllLocationsNear(center) {
   	var address = document.getElementById('addressInput').value;
   	if (address != 'all'){
 			var radius = '.58';
   	} else {
   		var radius = '30';
   	}		
   	
    var searchUrl = '/test/mastersearchlocations_genxml.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius;
     
     downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);
       var locationNodes = xml.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
       	      
    for (var i = 0; i < locationNodes.length; i++) {
         var fans = locationNodes[i].getAttribute("fans");
         var slug = locationNodes[i].getAttribute("slug");
         var pic = locationNodes[i].getAttribute("picture");
         var category = locationNodes[i].getAttribute("type");
         var organization = locationNodes[i].getAttribute("organization");
         var address = locationNodes[i].getAttribute("address");
         var latlng = new google.maps.LatLng(
              parseFloat(locationNodes[i].getAttribute("lat")),
              parseFloat(locationNodes[i].getAttribute("lng")));

         var place = createLocationMarker (latlng, fans, organization, category, pic, address, fans);
    	bounds.extend(latlng);
    	
       }
      });
}

function searchAllEventsNear(center) {
	var address = document.getElementById('addressInput').value;
	if (address != 'all'){
		var radius = '.58';
	} else {
		var radius = '30';
	}
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
     
     if (bydatesearch == 'block'){
     var searchUrl = '/test/searchevents_genxml.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius + '&startdate=' + startdate + '&timespan=' + timespan + '&limitvalue=' + limitvalue;
     } else {
     	var searchUrl = '/test/searcheventsdatepick_genxml.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius + '&startdate=' + sdatepick + '&enddate=' + edatepick + '&limitvalue=' + limitvalue;     	
     }
       downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);
       var markerNodes = xml.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
    var sidebar = document.getElementById('event_sidebar');
       if (markerNodes.length == 0) {
         sidebar.innerHTML = '<div id="noresult"><b>Sorry! None of our event listings matched your search.</b><br><br><b>You could try...</b><br><p>Searching in a different neighborhood<br>Searching in all neighborhoods<br>Searching with a different keyword<br>Removing the keyword from your search<br>Or searching with a different date range</p></div>';
         return;
       }
       if (markerNodes.length < 10) {
       	document.getElementById('loadmore').value = 'There are no more search results';
       } else {
       	document.getElementById('loadmore').value = 'Load Next 10 Events';
       }
    for (var i = 0; i < markerNodes.length; i++) {
         var name = markerNodes[i].getAttribute("ename");
         var eventid = markerNodes[i].getAttribute("eventid");
         var address = markerNodes[i].getAttribute("address");
         var sdate = markerNodes[i].getAttribute("start_date");
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
         
         var m_names = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
         var d = new Date(sdate);
         var s_day = d.getDate() + new Number(1);
         var s_month = d.getMonth();
         var s_year = d.getFullYear();
		 var event_date = m_names[s_month] + " " + s_day + ", " + s_year;


         var marker = createEventMarker(latlng, name, address, fans, organization, category, event_date, stime, etime, pic, slug, eventid);
         bounds.extend(latlng);

   		 var eventEntry = createEventSidebar(marker, name, address, distance, fans, organization, category, event_date, stime, etime, pic, slug, latlng, description, eventid, sdate, edate);
         sidebar.appendChild(eventEntry);
       }
map.fitBounds(bounds);
      });
}

function searchAllKeyEventsNear(center) {
var address = document.getElementById('addressInput').value;
	if (address != 'all'){
		var radius = '.58';
	} else {
		var radius = '30';
	}
	
     var today = new Date();
     var month = today.getMonth()+1;
     var day = today.getDate() + new Number(1);
     var year = today.getFullYear();
     var startdate = + year + "-" + month + "-" + day;
     var sdatepick = document.getElementById('startdate').value;
     var edatepick = document.getElementById('enddate').value;
     var timespan = document.getElementById('spanSelect').value;
     var searchword = document.getElementById('searchword').value;
     var limitvalue = document.getElementById('limitvalue').value;
     var bydatesearch = document.getElementById('bydate').style.display;
     
     if (bydatesearch == 'block'){
     var searchUrl = '/test/searcheventsall_genxml.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius + '&startdate=' + startdate + '&timespan=' + timespan + '&searchword=' + searchword + '&limitvalue=' + limitvalue;
     } else {
     var searchUrl = '/test/searcheventsalldatepick_genxml.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius + '&startdate=' + sdatepick + '&enddate=' + edatepick + '&searchword=' + searchword + '&limitvalue=' + limitvalue;    	
     }
       downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);
       var markerNodes = xml.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
    var sidebar = document.getElementById('event_sidebar');
       if (markerNodes.length == 0) {
         sidebar.innerHTML = '<div id="noresult"><b>Sorry! None of our event listings matched your search.</b><br><br><b>You could try...</b><br><p>Searching in a different neighborhood<br>Searching in all neighborhoods<br>Searching with a different keyword<br>Removing the keyword from your search<br>Or searching with a different date range</p></div>';
         return;
       }
       if (markerNodes.length < 10) {
       	document.getElementById('loadmore').value = 'There are no more search results';
       } else {
       	document.getElementById('loadmore').value = 'Load Next 10 Events';
       }
    for (var i = 0; i < markerNodes.length; i++) {
         var name = markerNodes[i].getAttribute("ename");
         var eventid = markerNodes[i].getAttribute("eventid");
         var address = markerNodes[i].getAttribute("address");
         var sdate = markerNodes[i].getAttribute("start_date");
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
         
         var m_names = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
         var d = new Date(sdate);
         var s_day = d.getDate() + new Number(1);
         var s_month = d.getMonth();
         var s_year = d.getFullYear();
		 var event_date = m_names[s_month] + " " + s_day + ", " + s_year;


         var marker = createEventMarker(latlng, name, address, fans, organization, category, event_date, stime, etime, pic, slug, eventid);
         bounds.extend(latlng);

   		 var eventEntry = createEventSidebar(marker, name, address, distance, fans, organization, category, event_date, stime, etime, pic, slug, latlng, description, eventid, sdate, edate);
         sidebar.appendChild(eventEntry);
       }
map.fitBounds(bounds);
      });
}
  
function createLocationMarker(latlng, fans, organization, category, pic, address, fans) {
	var type = category;
	var icontype;
	if (type == 'FOOD/GROCERY' && fans < 2000){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/place_dots/food.png',
					new google.maps.Size(12,12),
					new google.maps.Point(0,0),
					new google.maps.Point(0,0));
		var locationvalue = 1;
		var attraction = 'no';
	}
	else if (type == 'ARTS/ENTERTAINMENT/NIGHTLIFE' && fans < 2000){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/place_dots/theaterfilm.png',
					new google.maps.Size(12,12),
					new google.maps.Point(0,0),
					new google.maps.Point(0,0));
		var locationvalue = 1;
		var attraction = 'no';
	}
	else if (type == 'SHOPPING/RETAIL' && fans < 2000){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/place_dots/shopping.png',
					new google.maps.Size(12,12),
					new google.maps.Point(0,0),
					new google.maps.Point(0,0));
		var locationvalue = 1;
		var attraction = 'no';
	}
	else if (type == 'LOCAL BUSINESS' && fans < 2000){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/place_dots/genfun.png',
					new google.maps.Size(12,12),
					new google.maps.Point(0,0),
					new google.maps.Point(0,0));
		var locationvalue = 1;
		var attraction = 'no';
	}
	else if (type == 'MUSEUM' && fans < 2000){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/place_dots/museum.png',
					new google.maps.Size(12,12),
					new google.maps.Point(0,0),
					new google.maps.Point(0,0));
		var locationvalue = 1;
		var attraction = 'no';
	}
	else if (type == 'SPORTS' && fans < 2000){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/place_dots/spots.png',
					new google.maps.Size(12,12),
					new google.maps.Point(0,0),
					new google.maps.Point(0,0));
		var locationvalue = 1;
		var attraction = 'no';
	}
	else if (type == 'CONCERT VENUE' && fans < 2000){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/place_dots/music.png',
					new google.maps.Size(12,12),
					new google.maps.Point(0,0),
					new google.maps.Point(0,0));
		var locationvalue = 1;
		var attraction = 'no';
	}
	else if (type == 'FOOD/GROCERY' && fans > 2000) {
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/place_dots/attractions.png',				
					new google.maps.Size(12,12),
					new google.maps.Point(0,0),
					new google.maps.Point(0,0));
		var locationvalue = 2;
		var attraction = 'attraction';	
	}
	else if (type == 'ARTS/ENTERTAINMENT/NIGHTLIFE' && fans > 2000) {
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/place_dots/attractions.png',				
					new google.maps.Size(12,12),
					new google.maps.Point(0,0),
					new google.maps.Point(0,0));
		var locationvalue = 2;
		var attraction = 'attraction';		
	}
	else if (type == 'SHOPPING/RETAIL' && fans > 2000) {
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/place_dots/attractions.png',				
					new google.maps.Size(12,12),
					new google.maps.Point(0,0),
					new google.maps.Point(0,0));	
		var locationvalue = 2;
		var attraction = 'attraction';	
	}
	else if (fans > 2000) {
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/place_dots/attractions.png',				
					new google.maps.Size(12,12),
					new google.maps.Point(0,0),
					new google.maps.Point(0,0));
		var locationvalue = 2;
		var attraction = 'attraction';	
	
	}
	else {
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/place_dots/genfun.png',
					new google.maps.Size(12,12),
					new google.maps.Point(0,0),
					new google.maps.Point(0,0));
		var locationvalue = 1;
		var attraction = 'no';	
	}
	
	var html = "<div class=\"infowindowlocation\"><img src=\"" + pic + "\"><b>" + organization + "</b><br>" + address + "<br/><a target=\"_blank\" href=\"http://maps.google.com/maps?saddr=&daddr=" + latlng + "\">Get Directions</a><br/><br/></div>";
	
	var customdata = {
		category: type,
		location: locationvalue,
		attraction: attraction,
	}
	
	var place = new google.maps.Marker({
		map: map,
		position: latlng,
		icon: icontype,
		data: customdata,
		visible: false,
	});
	google.maps.event.addListener(place, 'click', function() {
		infoWindow.setContent(html);
		infoWindow.open(map, place);
	});
	return places[places.push(place)-1];
}
    
function createEventMarker(latlng, name, address, fans, organization, category, event_date, stime, etime, pic, slug, eventid) {
	var icontype;
	if (category == 'Food & Drink'){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/newplace_markers/food.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Music & Arts'){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/newplace_markers/theaterfilm.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Shopping'){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/newplace_markers/retail.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Sports & Outdoors'){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/newplace_markers/outdoors.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Educational'){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/newplace_markers/education.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Arts'){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/newplace_markers/arts.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else {
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/test/newplace_markers/genfun.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));	
	}
	
	var html = "<div class=\"infowindow\"><b>" + name + "</b><br/>" + event_date + "<br/>" + stime + " - " + etime + "<br/><a target=\"_blank\" href=\"http://www.onlyinpgh.com/test/events/event/" + slug + "/\">Get more info</a><br/><br/></div>";
	
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
    
function createEventSidebar(marker, name, address, distance, fans, organization, category, event_date, stime, etime, pic, slug, latlng, description, eventid, sdate, edate) {
      var eventdiv = document.createElement('div');
      isloggedin = document.getElementById("isloggedin").value;
      
      if (isloggedin == 'y'){
      var userid = document.getElementById("loggedinid").value;
      var html = "<div class=\"eside\"><img src=\"" + pic + "\"> <b>" + name + "</b> <br/><p class=\"hostedby\">Hosted By</p><p class=\"host\">" + organization + "</p>" + address + "<br/><a target=\"_blank\" href=\"http://maps.google.com/maps?saddr=&daddr=" + address + "\">Get Directions</a><br/><br/>" + event_date + "<br/>" + stime + " - " + etime + "<br/><br/>" + description + "... <a target=\"_blank\" href=\"http://www.onlyinpgh.com/test/events/event/" + slug + "/\">Get more info</a><br/><br/><input type=\"button\" value=\"Check In\" id=\"" + eventid + "button\" onclick=\"attendEvent(" + eventid + ")\"></div>";
      } else {
      	var html = "<div class=\"eside\"><img src=\"" + pic + "\"> <b>" + name + "</b> <br/><p class=\"hostedby\">Hosted By</p><p class=\"host\">" + organization + "</p>" + address + "<br/><a target=\"_blank\" href=\"http://maps.google.com/maps?saddr=&daddr=" + address + "\">Get Directions</a><br/><br/>" + event_date + "<br/>" + stime + " - " + etime + "<br/><br/>" + description + "... <a target=\"_blank\" href=\"http://www.onlyinpgh.com/test/events/event/" + slug + "/\">Get more info</a><br/><br/><input type=\"button\" value=\"Please login to check in\" id=\"attending\"></div>";
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

function attendEvent(eid) {
   	var user = document.getElementById("loggedinid").value;
   	
   	var searchUrl = '/test/insertattend.php?&userid=' + user + '&eventid=' + eid;       	
       downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);       
       var success = xml.documentElement.getElementsByTagName("success");
       
    for (var i = 0; i < successNodes.length; i++) {
         var message = successNodes[i].getAttribute("message");
       }
       document.getElementById(eid).value = message;
      });
}


    
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

</script>

<script type="text/javascript">
    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'none')
          e.style.display = 'block';
       else
          e.style.display = 'none';
    }
</script>

<script type="text/javascript">

function textChange(){
document.getElementById("attending").value="You are attending this event.";
}

function showSearch()
{
document.getElementById("datepicksearch").style.display = "block";
document.getElementById("bydate").style.display = "none";
}

function hideSearch()
{
document.getElementById("datepicksearch").style.display = "none";
document.getElementById("bydate").style.display = "block";
}

function showPlace() {
document.getElementById("resultsholder").style.display = "none";
document.getElementById("event").className = document.getElementById("event").className.replace(/\bsidebartogglecurrent\b/,'');
document.getElementById("event").className += " sidebartoggle";
document.getElementById("location_sidebar").style.display = "block";
document.getElementById("place").className += " sidebartogglecurrent";
}

function showEvent() {
	document.getElementById("resultsholder").style.display = "block";
	document.getElementById("event").className += " sidebartogglecurrent";
	document.getElementById("location_sidebar").style.display = "none";
	document.getElementById("place").className = document.getElementById("place").className.replace(/\bsidebartogglecurrent\b/,'');
	document.getElementById("place").className += " sidebartoggle";
}

function showKey() {
	var see = document.getElementById("mapkey").style.display;
	if (see == 'none'){
		document.getElementById("mapkey").style.display = "block";
	} else if (see =='block') {
	    document.getElementById("mapkey").style.display = "none";
	}
}

function checkAll(field){
{
for (i = 0; i < field.length; i++)
	field[i].checked = true ;
}
}
</script>


<script type="text/javascript">
	jQuery(function() {
		jQuery( ".datepicker" ).datepicker({dateFormat: 'yy-mm-dd'});
	});
	
	function clearChecks() {
	jQuery('#locationtypes input:checkbox').removeAttr('checked');
	};
	
	</script>

<link href='http://fonts.googleapis.com/css?family=Francois+One' rel='stylesheet' type='text/css'>
</head>


<body style="margin:0px; padding:0px;" onload="load()">
<?php do_action( 'bp_before_header' ) ?>
<div id="header"><div id="photoborder"></div>
<div class="header_content">
<div id="topbar">
	<?php if ( is_user_logged_in() ) : ?>

		<?php do_action( 'bp_before_sidebar_me' ) ?>
		<input type="hidden" value="y" id="isloggedin">
		<input type="hidden" value="<?php echo bp_loggedin_user_id() ?>" id="loggedinid">
		<div id="sidebar-me">
			<a class="showinmodal" href="<?php echo bp_loggedin_user_domain() ?>">
				<?php bp_loggedin_user_avatar( 'type=thumb&width=20&height=20' ) ?>
			</a>

			<?php echo bp_core_get_userlink( bp_loggedin_user_id() ); ?> | 
<?php wp_loginout($_SERVER['REQUEST_URI']); ?>

			<?php do_action( 'bp_sidebar_me' ) ?>
		</div>

		<?php do_action( 'bp_after_sidebar_me' ) ?>

		<?php if ( function_exists( 'bp_message_get_notices' ) ) : ?>
			<?php bp_message_get_notices(); /* Site wide notices to all users */ ?>
		<?php endif; ?>

	<?php else : ?>
		<input type="hidden" value="n" id="isloggedin">
		<a href="/wp-login.php?action=register" class="simplemodal-register">Register</a> | <?php wp_loginout($_SERVER['REQUEST_URI']); ?> | <?php jfb_output_facebook_btn(); jfb_output_facebook_callback(); ?>
	<?php endif; ?>
</div><!--Closes topbar-->
<div id="header_left">
<div class="logo">
<a href="<?php bloginfo('url'); ?>" title="Home">
<img src="http://www.onlyinpgh.com/test/wp-content/uploads/2011/08/logoframe.png">
</a>
</div><!--Closes logo-->
</div><!--Closes headerleft-->
<?php do_action( 'bp_header' ) ?>
</div><!--Closes header_content1-->
<div id="header_below">
<div class="header_content">
<div id="header_navigation">
<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
</div> <!--Close header_navigation-->
</div> <!--Close header_content2-->
<?php do_action( 'bp_header' ) ?>
</div><!--Close header_below-->
</div><!--Closes header-->
<?php do_action( 'bp_after_header' ) ?>
<?php do_action( 'bp_before_container' ) ?>