//<![CDATA[
//Scripts that display map on the individual profile pages
var map;
    var markers = [];
    var places = [];
    var infoWindow;
 	var eventsidebar;
 	var isloggedin;
 	var placeservice;
//Loads the map on the page with given options
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
//Populates the map with data and resets the limit counter on load
	  populateProfileMap();
	  document.getElementById('limiitvalue').value = new Number(0);
}

//Sends place requests to Google Places based on current map bounds
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
     

//Creates markers for the Google Place results
function createMarker(place) {
		var ltype = place.types[0];	
	//Determines the map marker to display depending on the event type
if ((ltype == 'bakery')||(ltype == 'bar')||(ltype == 'cafe')||(ltype == 'food')||(ltype == 'grocery_or_supermarket')||(ltype == 'restaurant')){
		var placedot = 'http://www.onlyinpgh.com/place_dots/food.png';
	} 
	else if (ltype == 'night_club') {
var placedot = 'http://www.onlyinpgh.com/place_dots/music.png';
	} 
	else if ((ltype == 'art_gallery')||(ltype == 'museum')) {
var placedot = 'http://www.onlyinpgh.com/place_dots/museum.png';
	} 
	else if ((ltype == 'bowling_allery')||(ltype == 'campground')||(ltype == 'park')||(ltype == 'stadium')) {
		var placedot = 'http://www.onlyinpgh.com/place_dots/spots.png';
	} 
	else if ((ltype == 'beauty_salon')||(ltype == 'bicycle_store')||(ltype == 'book_store')||(ltype == 'clothing_store')||(ltype == 'convenience_store')||(ltype == 'department_store')||(ltype == 'electronics_store')||(ltype == 'florist')||(ltype == 'furniture_store')||(ltype == 'hair_care')||(ltype == 'hardware_store')||(ltype == 'home_goods_store')||(ltype == 'jewelry_store')||(ltype == 'liquor_store')||(ltype == 'pet_store')||(ltype == 'pharmacy')||(ltype == 'shoe_store')||(ltype == 'shopping_mall')||(ltype == 'spa')||(ltype == 'store')) {
		var placedot = 'http://www.onlyinpgh.com/place_dots/shopping.png';
	} 
	else if (ltype == 'movie_theater') {
var placedot = 'http://www.onlyinpgh.com/place_dots/theaterfilm.png';
	} 
	else if ((ltype == 'amusement_park')||(ltype == 'aquarium')||(ltype == 'casio')||(ltype == 'library')||(ltype == 'university')){
var placedot = 'http://www.onlyinpgh.com/place_dots/attractions.png';
	}	
		  var placeLoc = place.geometry.location;
          var marker = new google.maps.Marker({
              map: map,
              icon: placedot,
              position: new google.maps.LatLng(placeLoc.lat(), placeLoc.lng())
          });
          var innerHTML = "<div class=\"infowindow\"><b>" + place.name + "</b><br>" + place.vicinity + "<br><br><a target=\"_blank\" href=\"http://maps.google.com/maps?saddr=&daddr=" + place.geometry.location + "\">Get Directions</a></div>";
          google.maps.event.addListener(marker, 'click', function () {
              infoWindow.setContent(innerHTML);
              infoWindow.open(map, this);
          });
 return places[places.push(marker)-1];
      }
      
      function clearPlaces() {
     infoWindow.close();
     for (var i = 0; i < places.length; i++) {
       places[i].setMap(null);
     }
     places.length = 0;  
}   
//Displays events attended by logged in user on their profile map
function populateProfileMap() {
   	 var today = new Date();
     var month = today.getMonth()+1;
     var day = today.getDate();
     var year = today.getFullYear();
     var startdate = + year + "-" + month + "-" + day;
     var userid = document.getElementById('loggedinid').value;
     var limit = document.getElementById('limitvalue').value;
   	//Performs query
   	var searchUrl = '/genevents_profilemap.php?startdate=' + startdate + '&userid=' + userid + '&limitvalue=' +limit;     	
   	//Gets XML and changes display text based on the results
       downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);
       var markerNodes = xml.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
    var sidebar = document.getElementById('event_sidebar');
       if (markerNodes.length == 0) {
         sidebar.innerHTML = '<div class="notattending"><b>You\'re currently not attending any events!</b><br><br><a href="www.onlyinpgh.com">Find out what\'s happening in Pittsburgh!</a></div>';
         document.getElementById('loadmore').style.display = 'none';
         return;
       }
       if (markerNodes.length < 10) {
       	document.getElementById('loadmore').value = 'There are no more search results';
       } else {
       	document.getElementById('loadmore').value = 'Load next 10 Events';
       }
       //Defines the variables with information from the XML
    for (var i = 0; i < markerNodes.length; i++) {
         var name = markerNodes[i].getAttribute("ename");
         var eventid = markerNodes[i].getAttribute("eventid");
         var address = markerNodes[i].getAttribute("address");
         var event_date = markerNodes[i].getAttribute("start_date");
         var stime = markerNodes[i].getAttribute("start_time");
         var etime = markerNodes[i].getAttribute("end_time");
         var description = markerNodes[i].getAttribute("description");
         var slug = markerNodes[i].getAttribute("slug");
         var pic = markerNodes[i].getAttribute("picture");
         var fans = markerNodes[i].getAttribute("org_fancount");
         var eventtype = markerNodes[i].getAttribute("type").split(',');
		 var category = eventtype[0];
         var organization = markerNodes[i].getAttribute("organization");
         var distance = parseFloat(markerNodes[i].getAttribute("distance"));
         var latlng = new google.maps.LatLng(
              parseFloat(markerNodes[i].getAttribute("lat")),
              parseFloat(markerNodes[i].getAttribute("lng")));

//Creates a map marker and sidebar entry for each event result
         var marker = createEventMarker(latlng, name, address, organization, category, event_date, stime, etime, pic, slug, eventid, fans, description);
         bounds.extend(latlng);

   		 var eventEntry = createEventSidebar(marker, name, address, distance, organization, category, event_date, stime, etime, pic, slug, latlng, description, eventid);
         sidebar.appendChild(eventEntry);
       }
      });
}

//Loads next group of events in any exist
function searchTen() {
	 var limit = document.getElementById('limitvalue').value;
	 //Change this number based on the size of continued results you want to retrieve
	 var newlimit = new Number(limit) + new Number(10);
	 document.getElementById('limitvalue').value = newlimit;
	 
	 populateProfileMap();
	 
	 
}
//Creates marker for each event result
function createEventMarker(latlng, name, address, organization, category, event_date, stime, etime, pic, slug, eventid, fans, description) {
	   		isloggedin = document.getElementById("isloggedin").value;
	   		var user = document.getElementById("loggedinid").value;
	//Determines the marker image based on category
	var icontype;
	if (category == 'Food &amp; Drink' && fans < 100){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/food.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Theater' && fans < 100){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/theaterfilm.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Shopping' && fans < 100){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/retail.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Sports &amp; Outdoors' && fans < 100){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/outdoors.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Educational' && fans < 100){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/education.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Arts' && fans < 100){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/arts.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Music' && fans < 100){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/music.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	//If event comes from a feed the event_pic becomes the map marker
	else if (category == 'oip_feeds' && fans < 100){
		var icontype = pic;
	}
	else if (category == 'General Fun' && fans < 100) {
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/genfun.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));	
	}
	
	else if (category == 'Food &amp; Drink' && fans > 100){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/food_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Theater' && fans > 100){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/theaterfilm_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Shopping' && fans > 100){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/retail_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Music' && fans > 100){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/music_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Sports &amp; Outdoors' && fans > 100){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/outdoors_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Educational' && fans > 100){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/education_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'Arts' && fans > 100){
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/arts_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));
	}
	else if (category == 'oip_feeds' && fans > 100){
		var icontype = pic;
	}
	else if (category == 'General Fun' && fans > 100) {
		var icontype = new google.maps.MarkerImage('http://onlyinpgh.com/newplace_markers/genfun_attraction.png',
					new google.maps.Size(20, 20),
					new google.maps.Point(0,0),
					new google.maps.Point(0, 20));	
	}	
      	var html = "<div class=\"infowindow\"><img src=\"" + pic + "\"><b>" + name + "</b><br/>" + event_date + "<br/>" + stime + " - " + etime + "<br/><br/>" + description + "...<a target=\"_blank\" href=\"http://www.onlyinpgh.com/events/event/" + slug + "/\">Get more info</a><br/><br/></div>";

	//Used for toggeling the event markers
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
//Creates sidebar entry for each event result
function createEventSidebar(marker, name, address, distance, organization, category, event_date, stime, etime, pic, slug, latlng, description, eventid, edate) {
      var eventdiv = document.createElement('div');
      isloggedin = document.getElementById("isloggedin").value;
      
      	var html = "<div class=\"eside\"><img src=\"" + pic + "\"> <b>" + name + "</b> <br/><p class=\"hostedby\">Hosted By</p><p class=\"host\">" + organization + "</p>" + address + "<br/><a target=\"_blank\" href=\"http://maps.google.com/maps?saddr=&daddr=" + address + "\">Get Directions</a><br/><br/>" + event_date + "<br/>" + stime + " - " + etime + "<br/><br/>" + description + "... <a target=\"_blank\" href=\"http://www.onlyinpgh.com/events/event/" + slug + "/\">Get more info</a><br/><br/></div>";
      
      eventdiv.innerHTML = html;
      eventdiv.style.cursor = 'pointer';
      //Listeners to change background color on hover and open infowindow on click
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

//Call back scripts for Google Maps results
//If any scripts need to be added that depend on markers existing on the map ADD THEM ABOVE THESE SCRIPTS
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